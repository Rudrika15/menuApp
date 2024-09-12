@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Table List</h2>
    </div>
</div>

@if($table->count() !== 0)
    @foreach ($table as $item)
    <div class="card mb-3">
        <div class="card-header text-dark text-center">
            Table No: {{ $item->tableNumber }}
        </div>
        <div class="card-body">
            <table  id="table-{{ $item->tableNumber }}" class="table table-bordered text-center bg-white">
               <thead>
               </thead>
               <tbody>
               </tbody>
            </table>
                <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-outline-info mx-2" data-toggle="modal" data-target="#addItemModal"  data-table-number="{{ $item->tableNumber }}">
                    <i class="fa fa-plus"></i> Add Items
                </a>
                <button class="btn btn-outline-warning mx-2 " id="generateOrderButton-{{ $item->tableNumber }}" data-table-number="{{ $item->tableNumber }}"  style="display: none;" onclick="generate({{ $item->tableNumber }})">
                    <i class="fa fa-check"></i>
                    Generate Order</button>
                </div>
        </div>
    </div>
    @endforeach
@else
<div style="text-align: center; font-size: 2.5rem; margin-top: 76px">
    Record Not Found
</div>
@endif
<!-- Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addItemModalLabel">Items</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            @foreach ($menu as $item)
            <div class="row mb-3">
                <div class="col-md-8">
                  <h5>{{ $item->title }}</h5>
                  <p>Price: {{ $item->price }}</p>
                </div>
                <div class="col-md-4">
                  <img src="menuImage/{{ $item->photo }}" class="img-fluid" alt="{{ $item->title }}">
                </div>
                <div class="col-md-12 mt-2">
                    <button class="btn btn-sm btn-outline-primary select-item" data-title="{{ $item->title }}" data-photo="menuImage/{{ $item->photo }}" data-price="{{ $item->price }}" data-id="{{ $item->id }}">Select Item</button>
                </div>
              </div>
            @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  

  <!-- Selected item details modal -->
<div class="modal fade" id="selectedItemModal" tabindex="-1" role="dialog" aria-labelledby="selectedItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectedItemModalLabel">Selected Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="selectedItemDetails">
                    <img id="selectedItemImage" src="" class="img-fluid" alt="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                    <h5 id="selectedItemTitle"></h5>
                    <p>Price: <span id="selectedItemPrice"></span></p>
                    <p>ID: <span id="selectedItemId"></span></p> 
                    <div class="quantity-selector">
                        <button type="button" id="decreaseQuantity" class="btn btn-secondary">-</button>
                        <input type="text" id="quantity" value="1" readonly class="form-control d-inline-block" style="width: 60px; text-align: center;">
                        <button type="button" id="increaseQuantity" class="btn btn-secondary">+</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" id="backToItemsButton">Back to Items</a> <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary" id="confirmItem">Add to Order</button>
            </div>
        </div>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let tableNumber;
            let headersAdded = {}; 
            document.querySelectorAll('.select-item').forEach(function(button) {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    const photo = this.getAttribute('data-photo');
                    const price = this.getAttribute('data-price');
                    
                    document.getElementById('selectedItemId').textContent = id;
                    document.getElementById('selectedItemTitle').textContent = title;
                    document.getElementById('selectedItemImage').src = photo;
                    document.getElementById('selectedItemPrice').textContent = price;
                    
                    $('#addItemModal').modal('hide');
                    $('#selectedItemModal').modal('show');
                });
            });
        
            document.getElementById('increaseQuantity').addEventListener('click', function() {
                const quantityInput = document.getElementById('quantity');
                let quantity = parseInt(quantityInput.value);
                quantityInput.value = quantity + 1;
            });
        
            document.getElementById('decreaseQuantity').addEventListener('click', function() {
                const quantityInput = document.getElementById('quantity');
                let quantity = parseInt(quantityInput.value);
                if (quantity > 1) {
                    quantityInput.value = quantity - 1;
                }
            });
        
            document.getElementById('confirmItem').addEventListener('click', function() {
                const id = document.getElementById('selectedItemId').textContent;
                const photo = document.getElementById('selectedItemImage').src;
                const title = document.getElementById('selectedItemTitle').textContent;
                const price = document.getElementById('selectedItemPrice').textContent;
                const quantity = document.getElementById('quantity').value;
                const table = document.querySelector(`#table-${tableNumber} tbody`);
                if (!headersAdded[tableNumber]) {
                const tableHead = document.createElement('thead');
                tableHead.innerHTML = `
                    <tr>
                    <th>Image</th>
                    <th>Items</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>    
                    </tr>
                `;
                table.parentElement.insertBefore(tableHead, table);
                headersAdded[tableNumber] = true; 
                }
                const tableRow = document.createElement('tr');
                tableRow.innerHTML = `
                    <td><img src="${photo}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;"></td>
                    <td>${title}</td>
                    <td>${quantity}</td>
                    <td>${price}</td>
                    <td><button class="btn btn-sm btn-danger remove-item">Remove</button></td>
                `;
                table.appendChild(tableRow);
                
                document.getElementById('quantity').value = '1';
                $('#selectedItemModal').modal('hide');
                document.getElementById(`generateOrderButton-${tableNumber}`).style.display = 'inline-block';
            });

            document.addEventListener('click',function(event){
                if (event.target && event.target.classList.contains('remove-item')) {
                const row = event.target.closest('tr');
                row.remove();
                }
            });

            document.getElementById('backToItemsButton').addEventListener('click', function() {
                $('#selectedItemModal').modal('hide');
                $('#addItemModal').modal('show');
            });

            document.querySelectorAll('[data-toggle="modal"]').forEach(function(button) {
                button.addEventListener('click', function() {
                    tableNumber = this.getAttribute('data-table-number'); 
                });
            });

        });

function generate(tableNumber) {
    let items = [];

    // Collect item_id and quantity from the table rows specific to this tableNumber
    document.querySelectorAll(`#table-${tableNumber} tbody tr`).forEach(function(row) {
        let itemId = row.getAttribute('data-item-id'); // Assuming data-item-id holds the item ID
        let quantity = row.querySelector('.item-quantity'); // Get the quantity
        
        if (itemId && quantity) {
            items.push({
                id: itemId,
                quantity: quantity
            });
        }
    });

    // Debug: log collected items
    console.log('Table:', tableNumber);
    console.log('Collected Items:', items);

    // Send the data via AJAX to the server
    $.ajax({
        url: "{{ route('order.generate') }}",  // Update this route as needed
        method: 'POST',
        data: {
            _token: "{{ csrf_token()}}",  // CSRF token for security
            tableNumber: tableNumber,
            items: items
        },
        success: function(response) {
            if (response.success) {
                alert('Order generated successfully!');
                // Optionally: update UI or redirect
            } else {
                alert('Failed to save the order.');
            }
        },
        error: function() {
            alert('Error occurred while saving the order.');
        }
    });
}
    </script>
@endsection