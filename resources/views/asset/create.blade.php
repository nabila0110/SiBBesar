<h1>Create Asset</h1>
<form method="POST" action="{{ route('assets.store') }}">
    @csrf
    <input name="asset_name" />
    <button type="submit">Save</button>
</form>
