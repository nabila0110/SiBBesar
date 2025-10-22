<h1>Create Receivable</h1>
<form method="POST" action="{{ route('receivables.store') }}">
    @csrf
    <input name="customer_name" />
    <input name="amount" />
    <button type="submit">Save</button>
</form>
