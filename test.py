import os

# Define the root path where your project will be located
root_path = os.path.join(os.getcwd(), "event_management")  # You can change this to any desired directory

# List of folders and files to create
folders_and_files = {
    "app/config": [
        "database.php", 
        "app.php", 
        "ai.php"
    ],
    "app/controllers": [
        "EventController.php", 
        "UserController.php", 
        "TicketController.php", 
        "AIController.php"
    ],
    "app/models": [
        "Event.php", 
        "User.php", 
        "Ticket.php", 
        "Recommendation.php"
    ],
    "app/views": [
        "events.php", 
        "event_details.php", 
        "login.php", 
        "registration.php", 
        "profile.php", 
        "checkout.php"
    ],
    "app/public/assets/css": [
        "style.css"  # Example CSS file
    ],
    "app/public/assets/js": [
        "app.js"  # Example JS file
    ],
    "app/public/assets/images": [],
    "app/routes": [
        "web.php", 
        "api.php"
    ],
    "app/storage/uploads": [],
    "app/storage/cache": [],
    "app/storage/logs": [],
    "app/tests": [
        "EventTest.php", 
        "TicketTest.php", 
        "UserTest.php", 
        "RecommendationTest.php"
    ],
    "app/migrations": [
        "2025_04_01_create_events_table.php", 
        "2025_04_01_create_users_table.php", 
        "2025_04_01_create_tickets_table.php"
    ],
    "app/translations": [
        "en.php", 
        "fr.php"
    ]
}

# Function to create folders and files
def create_folders_and_files(base_path, structure):
    for folder, files in structure.items():
        folder_path = os.path.join(base_path, folder)
        # Create the folder if it doesn't exist
        if not os.path.exists(folder_path):
            os.makedirs(folder_path)
            print(f"Created folder: {folder_path}")
        
        # Create files in the folder
        for file in files:
            file_path = os.path.join(folder_path, file)
            if not os.path.exists(file_path):
                with open(file_path, 'w') as f:
                    # Write a basic placeholder content to each file
                    f.write(f"<?php\n// {file} - Placeholder file\n?>")
                print(f"Created file: {file_path}")
            else:
                print(f"File already exists: {file_path}")

# Create the folders and files
create_folders_and_files(root_path, folders_and_files)
