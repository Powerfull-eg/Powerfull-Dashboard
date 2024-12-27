$(document).ready(function() {
    $('form#incomplete-manual').on('submit', function(e) {
        e.preventDefault();
        const orders = [];
        const selectedRequestValue = $('input[name="incomplete-request"]:checked').val();
        $('input[name="incomplete-operation"]').each(function() {
            if(selectedRequestValue == '2') {
                orders.push($(this).val());
            }
            else if(this.checked) {
                orders.push($(this).val());
            }
        });
        $('input[name="orders"]').val(orders);
        // console.log(orders,selectedRequestValue);
        this.submit();
    });
});

function editIncompleteOrder(order,deleteOrder = false) {
    let content;
    if(deleteOrder) {
        content = '<h5 class="text-center">Are you sure you want to delete this order?</h5>' + 
        '<input type="hidden" name="order" value="' + order + '" />' +
        '<input type="hidden"class="amount form-control" name="amount" value="0" />';
    }else{
        content =  '<div class="form-group">' +
        '<label>New Amount</label>' +
        '<input type="hidden" name="order" value="' + order + '" />' +
        '<input type="number" name="amount" placeholder="Order New Amount" class="amount form-control" required />' +
        '</div>';
    }
    $.confirm({
        title: deleteOrder ? 'Delete Order' : 'Edit Order',
        content: '' +
        '<form action="/dashboard/payments/incomplete/edit-amount" method="POST" class="edit-order" id="edit-order-' + order + '">' +
        '<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') + '" />' +
        content +
        '</form>',
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    var amount = this.$content.find('.amount').val();
                    if(!amount && !deleteOrder) {
                        $.alert('provide a valid amount.');
                        return false;
                    }
                    this.$content.find('#edit-order-' + order).submit();
                }
            },
            cancel: function () {
                //close
            },
        },
    });
}

function showIncompleteOrder(order) {
    $.confirm({
        title: 'Order Info',
        content: async function () {
            let orderInfo = await $.get('operation/' + order);
            console.log(orderInfo);
            this.setContent(`<div class="table-responsive">  <table class="table table-vcenter table-nowrap">
                        <thead>
                            <tr class="table-secondary">
                                <th>CustomerName</th>
                                <th>Amount</th>
                                <th>Shop</th>
                                <th>Device</th>
                                <th>Borrow Time</th>
                                <th>Return Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${orderInfo.user.first_name + ' ' + orderInfo.user.last_name}</td>
                                <td>${orderInfo.amount}</td>
                                <td>${orderInfo.device.shop.name}</td>
                                <td>${orderInfo.device.device_id}</td>
                                <td>${orderInfo.borrowTime}</td>
                                <td>${orderInfo.returnTime}</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>`)
        },
        buttons: {
            cancel: function () {
                //close
            },
        },
    });
}

// Delete Account
function deleteAccount() {
    $.confirm({
        title: 'Delete Account',
        content: 'Are you sure you want to delete this account?',
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () { $('#delete-account')[0].submit(); }
            },
            cancel: function () {
                //close
            },
        },
    });
}

// Block Account
function blockAccount(user,action = 'block') {
    $.confirm({
        title: `${action == 'block' ? 'Block' : 'Unblock'} Account`,
        content: `Are you sure you want to ${action == 'block' ? 'block' : 'unblock'} this account?`,
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    const form = $('#block-account');
                    $(form)[0].action += '/' + user;
                    $(form)[0].submit();
                }
            },
            cancel: function () {
                //close
            },
        },
    });
}

// Delete order
function deleteOrder(order,restore = false) {
    $.confirm({
        title: restore ? 'Restore Order' : 'Delete Order',
        content: `Are you sure you want to ${restore ? 'restore' : 'delete'} this order?`,
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    const form = restore ? $('#restore-order') : $('#delete-order');
                    $(form)[0].action += '/' + order;
                    $(form)[0].submit();
                }
            },
            cancel: function () {
                //close
            },
        },
    });
}

// Close order 
function closeOrder(order) {
    $.confirm({
        title: 'Close Order',
        content: `Are you sure you want to close this order?`,
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    const form = $('#close-order');
                    $(form)[0].action += '/' + order;
                    $(form)[0].submit();
                }
            },
            cancel: function () {},
        },
    });    
}

// refund order amount
function refundOrder(order,orderAmount) {
    $.confirm({
        title: 'Refund Order',
        content: `Add the required refund amount of this order?
        <div class="form-group">
            <label>Refunded</label>
            <input type="number" name="amount" placeholder="Order Refunded Amount" class="amount form-control" required />
        </div>
        `,
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    const form = $('#refund-order');
                    const amount = this.$content.find('[name=amount]').val();
                    if(!amount || amount <= 0 || amount > orderAmount){
                        $.alert('Provide a valid amount');
                        return false;
                    }
                    $(form).append(`<input type="hidden" name="operation_id" value="${order}">`);
                    $(form).append(`<input type="hidden" name="amount" value="${amount}">`);
                    $(form)[0].submit();
                }
            },
            cancel: function () {},
        },
    });    
}

// Close order 
function resetPassword(user,userName) {
    $.confirm({
        title: 'Reset Password',
        content: `Are you sure you want to reset user ${userName} password?`,
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    const form = $('#reset-password');
                    $(form)[0].action += '/' + user;
                    const channels = $('input[name="resetPasswordChannels"]:checked').map(function () {
                        return this.value;
                    }).get();                    
                    $(form).append(`<input type="hidden" name="channels" value="${channels.join(',')}">`);
                    $('#page-overlay').hasClass('d-none') ? showPageLoader() : '';
                    $(form)[0].submit();
                }
            },
            cancel: function () {},
        },
    });    
}