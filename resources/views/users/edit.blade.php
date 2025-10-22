<h1>Edit User</h1>
<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf
    @method('PUT')
    <input name="name" value="{{ $user->name ?? '' }}" />
    <input name="email" value="{{ $user->email ?? '' }}" />
    <button type="submit">Save</button>
</form>
