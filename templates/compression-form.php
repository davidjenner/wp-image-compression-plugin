<form method="post" enctype="multipart/form-data">
   <label for="image">Upload Image:</label>
   <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png, .webp" required>
   <br>
   <br>
   <label for="compression-level">Compression Level:</label>
   <input type="range" name="compression-level" id="compression-level" min="0" max="100" value="60">
   <br>
   <br>
   <input type="submit" value="Compress Image(s)">
</form>