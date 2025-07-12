import os

def rename_all_to_jpg(directory):
    for filename in os.listdir(directory):
        filepath = os.path.join(directory, filename)

        # Skip directories
        if os.path.isdir(filepath):
            continue

        name, ext = os.path.splitext(filename)
        new_filename = f"{name}.jpg"
        new_filepath = os.path.join(directory, new_filename)

        try:
            os.rename(filepath, new_filepath)
            print(f"Renamed: {filename} -> {new_filename}")
        except Exception as e:
            print(f"Failed to rename {filename}: {e}")

# Replace with your actual directory path
rename_all_to_jpg("Actual Image Directory path :")
