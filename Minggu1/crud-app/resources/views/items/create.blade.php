<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Item</title>
</head>
<body>
    <h1>Add Item</h1>
    <form action="{{ route('items.store') }}" method="post">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br>
        <label for="description">Description</label>
        <textarea name="description" required></textarea>
        <br>
        <button type="submit">Add Item</button>
    </form>
</body>
</html>

<!--  -->