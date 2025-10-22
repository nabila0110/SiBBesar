<h1>Create Payable</h1>
<form method="POST" action="{{ route('payables.store') }}">
    @csrf
    <input name="vendor_name" />
    <input name="amount" />
    <button type="submit">Save</button>
</form>
