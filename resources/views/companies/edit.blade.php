<h1>Edit Company</h1>
<form method="POST" action="#">
    @csrf
    @method('PUT')
    <input name="name" />
    <button type="submit">Save</button>
</form>
