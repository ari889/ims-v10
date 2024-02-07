<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tr>
                <td><b>Customer Group</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->customer_group->group_name }}</td>
            </tr>
            <tr>
                <td><b>Name</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->name }}</td>
            </tr>
            <tr>
                <td><b>Company Name</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->company_name }}</td>
            </tr>
            <tr>
                <td><b>Vat Number</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->vat_number }}</td>
            </tr>
            <tr>
                <td><b>Phone No.</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->phone }}</td>
            </tr>
            <tr>
                <td><b>Email</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->email }}</td>
            </tr>
            <tr>
                <td><b>Address</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->address }}</td>
            </tr>
            <tr>
                <td><b>City</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->city }}</td>
            </tr>
            <tr>
                <td><b>State</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->state }}</b></td>
            </tr>
            <tr>
                <td><b>Postal Code</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->postal_code }}</td>
            </tr>
            <tr>
                <td><b>Country</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->country }}</td>
            </tr>
            <tr>
                <td><b>Status</b></td>
                <td><b>:</b></td>
                <td>{{ STATUS[$customer->status] }}</td>
            </tr>
            <tr>
                <td><b>Created By</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->created_by }}</td>
            </tr>
            <tr>
                <td><b>Updated By</b></td>
                <td><b>:</b></td>
                <td>{{ $customer->updated_by }}</td>
            </tr>
            <tr>
                <td><b>Joining Date</b></td>
                <td><b>:</b></td>
                <td>{{ date(config('settings.date_format'), strtotime($customer->created_at)) }}</td>
            </tr>
            <tr>
                <td><b>Updated Date</b></td>
                <td><b>:</b></td>
                <td>{{ date(config('settings.date_format'), strtotime($customer->updated_at)) }}</td>
            </tr>
        </table>
    </div>
</div>