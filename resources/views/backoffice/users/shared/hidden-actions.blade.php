<div class="d-none actions">
    <textarea id="flash-message">
        @if (Session::has('flash-message'))
            {{ Session::get('flash-message') }}
        @endif
    </textarea>
    <form action="{{ $formActions['deleteUser'] }}" method="post" id="deleteform">
        @csrf
        <input type="text" name="user-key" id="user-key">
    </form>
    <form action="{{ $formActions['disableUser'] }}" method="post" id="disableform">
        @csrf
        <input type="text" name="user-key" id="user-key">
    </form>
    <form action="{{ $formActions['enableUser'] }}" method="post" id="enableform">
        @csrf
        <input type="text" name="user-key" id="user-key">
    </form>
</div>