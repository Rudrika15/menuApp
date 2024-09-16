@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2>Table List</h2>
    </div>
</div>

@if($table->count() !== 0)
<div class="container">
    <div class="row">
        @foreach ($table as $item)
        <div class="col-sm-6 mb-sm-2">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"> Table No: {{ $item->tableNumber }}</h5>
                    <table id="table-{{ $item->tableNumber }}" class="table table-bordered text-center bg-white">
                        @if ($addtocart->where('tableId',$item->id)->count() > 0)

                        <thead>
                            <th>Items</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </thead>
                        @endif
                        <tbody>
                            @foreach ($addtocart->where('tableId', $item->id) as $cartItem)
                            <tr data-id="{{ $cartItem->menuId }}">
                                <td>{{ $cartItem->menu->title }}</td>
                                <td class="item-qty">{{ $cartItem->qty }}</td>
                                {{-- remove button --}}
                                <td><button class="btn btn-sm btn-outline-danger remove-item"
                                        data-id="{{$cartItem->id}}"><i class="fa fa-minus"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-outline-info mx-2" data-toggle="modal" data-target="#addItemModal"
                            data-table-number="{{ $item->tableNumber }}" data-table-id="{{ $item->id }}">
                            <i class="fa fa-plus"></i> Add Items
                        </a>
                        @if ($addtocart->where('tableId',$item->id)->count() > 0)
                        <button class="btn btn-outline-warning mx-2 " id="generateOrderButton-{{ $item->tableNumber }}"
                            data-table-number="{{ $item->tableNumber }}" data-target="#generateBillModal"
                            data-table-id="{{ $item->id }}"
                            onclick="openGenerateBillForm({{ $item->tableNumber }}, {{ $item->id }})">
                            <i class="fa fa-check"></i>
                            Generate Bill</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@else
<div style="text-align: center; font-size: 2.5rem; margin-top: 76px">
    Record Not Found
</div>
@endif
<!-- Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- search box --}}
                <input type="text" id="searchItem" class="form-control mb-3" placeholder="Search for items...">

                @foreach ($menu as $item) 
                {{-- search code start--}}
                <div class="row mb-3 m-0 item-row" data-title="{{strtolower($item->title)}}" >
                    {{-- search code over --}}
                    <div class="col-md-4">
                        <img src="menuImage/{{ $item->photo }}" class="img-fluid"
                            style="width: 250px; height: 150px; object-fit: cover; border-radius: 5px;">
                    </div>
                    <div class="col-md-6 d-flex flex-column justify-content-center">
                        <h5>{{ $item->title }}</h5>
                        <p>Price: {{ $item->price }}</p>
                        <button class="btn btn-sm btn-outline-primary select-item" data-title="{{ $item->title }}"
                            data-photo="menuImage/{{ $item->photo }}" data-price="{{ $item->price }}"
                            data-id="{{ $item->id }}"> Select Item</button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Selected item details modal -->
<div class="modal fade" id="selectedItemModal" tabindex="-1" role="dialog" aria-labelledby="selectedItemModalLabel"
    aria-hidden="true">
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
                    <img id="selectedItemImage" src="" class="img-fluid" alt=""
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                    <h5 id="selectedItemTitle"></h5>
                    <p>Price: <span id="selectedItemPrice"></span></p>
                    <p style="display: none;">ID: <span id="selectedItemId"></span></p>
                    <div class="quantity-selector">
                        <button type="button" id="decreaseQuantity" class="btn btn-secondary">-</button>
                        <input type="text" id="quantity" value="1" readonly class="form-control d-inline-block"
                            style="width: 60px; text-align: center;">
                        <button type="button" id="increaseQuantity" class="btn btn-secondary">+</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" id="backToItemsButton"><i class="fa fa-arrow-left"></i> Back to
                    Items</a> <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-sm btn-primary" id="confirmItem"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
            </div>
        </div>
    </div>
</div>

<!-- Generate Bill Modal -->
<div class="modal fade" id="generateBillModal" tabindex="-1" role="dialog" aria-labelledby="generateBillModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateBillModalLabel">Generate Bill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="generateBillForm">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="submitBill"><i class="fa fa-check"></i> Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
// items search 
document.addEventListener('DOMContentLoaded', function () {
    const searchItemInput = document.getElementById('searchItem');
    const itemRows = document.querySelectorAll('.item-row');
                
    searchItemInput.addEventListener('keyup', function () {
        const searchValue = searchItemInput.value.toLowerCase().trim();

        itemRows.forEach(function (row) {
            const title = row.getAttribute('data-title');
            if (title.includes(searchValue)) {
                row.style.display = ''; 
            } else {
                row.style.display = 'none';
            }
        });
    });
});
// delete AddToCart Order
document.addEventListener('DOMContentLoaded', function () {
    let tableId;
    let tableNumber;
    let headersAdded = {}; 
    document.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('remove-item')) {
            const button = event.target;
            const row = button.closest('tr');
            const itemId = button.getAttribute('data-id');
            const tableNumber = button.closest('.card').querySelector('.card-title').textContent.split(':')[1].trim();
            $.ajax({
                url: "{{ route('deleteaddtocart.order') }}", 
                method: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: itemId
                },
                success: function(response) {
                    if (response.message) {
                        toastr.success('Item removed successfully!');
                        row.remove()
                        setTimeout(function() {
                        window.location.reload();
                        }, 2000);  
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        }
    });

    document.querySelectorAll('.select-item').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const photo = this.getAttribute('data-photo');
            const price =parseFloat(this.getAttribute('data-price'));
                    
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
        
    // Store AddToCart Order
    document.getElementById('confirmItem').addEventListener('click', function() {
        const id = document.getElementById('selectedItemId').textContent;
        const quantity =parseInt(document.getElementById('quantity').value);
                
        $.ajax({
            url: "{{ route('addtocart.order') }}", 
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                menuId: id,
                qty: quantity,
                tableId: tableId
            },
            success: function(response) {
                toastr.success('Item added to order successfully!');
                setTimeout(function() {
                    window.location.reload();
                }, 2000); 

                document.getElementById('quantity').value = '1';
                $('#selectedItemModal').modal('hide');
                document.getElementById(`generateOrderButton-${tableNumber}`).style.display = 'inline-block';
                const tableRow = document.createElement('tr');
                tableRow.setAttribute('data-id', id);
                document.querySelector(`#table-${tableNumber} tbody`).appendChild(tableRow);
            },
            error: function(xhr) {
                toastr.error('Failed to add item to the order. Please try again.');
            }
        });
    });


    document.getElementById('backToItemsButton').addEventListener('click', function() {
        $('#selectedItemModal').modal('hide');
        $('#addItemModal').modal('show');
    });

    document.querySelectorAll('[data-toggle="modal"]').forEach(function(button) {
        button.addEventListener('click', function() {
            tableNumber = this.getAttribute('data-table-number'); 
            tableId = this.getAttribute('data-table-id');
        });
    })

});

// Store Order in order_masters and order_details Table
function openGenerateBillForm(tableNumber,tableId) {
    $('#generateBillModal').modal('show');
    $('#generateBillModal').data('tableNumber', tableNumber);
    $('#generateBillModal').data('tableId', tableId);

    $('#submitBill').on('click',function(){
        let items = [];
        const name = $('#name').val();
        const contactNumber = $('#contactNumber').val();
        const tableId = $('#generateBillModal').data('tableId');

        const rows = document.querySelectorAll(`#table-${tableNumber} tbody tr`);
        rows.forEach(function(row) {
        const itemId = row.getAttribute('data-id'); 
        const quantity = row.querySelector('td:nth-child(2)').textContent;
        items.push({
                menuId: itemId,
                qty: quantity,
            });
        });
        $.ajax({
            url:"{{ route('order.master') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                tableId: tableId,
                name: name,
                contactNumber: contactNumber,
                items: items,
            },
            success: function(response) {
                if(response.status){
                    toastr.success(response.message);
                    $('#submitBill').text('Submitted').prop('disabled',true);

                    setTimeout(function() {
                    window.location.reload();
                    }, 2000);  
                }else{
                    if (response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }else {
                        toastr.error('An error occurred. Please try again.');
                    }      
                }
            },
        });
    });
}
</script>

@endsection