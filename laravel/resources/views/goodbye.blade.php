<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goodbye</title>
</head>
<body style="background-color: #fff; color: #2c3e50; font-family: 'Arial', sans-serif; margin: 0;">
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; text-align: center;">
        <div style="background-color: #ecf0f1; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 28px; font-weight: bold; margin-bottom: 20px;">Thank you for using our service!</h3>
            <p style="font-size: 18px; margin-bottom: 30px;">Your account has been successfully deleted.</p>
            <!-- Cancel Button -->
            <button type="button" style="padding: 12px 30px; background-color: #e74c3c; color: #fff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;" onclick="window.location.href='{{ route('templates.index') }}'">
                Back
            </button>
        </div>
    </div>
</body>
</html>
