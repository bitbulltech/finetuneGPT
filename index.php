<!DOCTYPE html>
<html>
<head>
    <title>FineTune ChatGPT Model</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<style>
	.suggestion{
		
    background: antiquewhite;
    margin: 4px;
    padding: 4px;
    border-radius: 4px;
    font-size: 12px;
    width: 170px;
    display: inline;
	cursor:pointer;
	}
    </style>
</head>
<body>
 <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5">
			
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">FineTune ChatGPT Model</h5>
						<p>Experience the power of fine-tuning OpenAI's ChatGPT model effortlessly with our user-friendly web application. Simply upload your JSONL dataset, and enhance the conversational capabilities of ChatGPT for your specific needs.</p>
						<br><br>
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="api_key" class="form-label">ChatGPT API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" required>
                            </div>
                            <div class="mb-3">
                                <label for="gpt_model_name" class="form-label">GPT Model Name</label>
                                <input type="text" class="form-control" id="gpt_model_name" name="gpt_model_name" required>
                                <div class="suggestion-container">
                                    <span class="suggestion" data-suggestion="gpt-3.5-turbo-0613">gpt-3.5-turbo-0613 (recommended)</span>
                                    <span class="suggestion" data-suggestion="babbage-002">babbage-002</span>
                                    <span class="suggestion" data-suggestion="davinci-002">davinci-002</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="json_file" class="form-label">Choose a JSONL file</label>
                                <input type="file" class="form-control" id="json_file" name="json_file" accept=".jsonl" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const suggestionContainer = document.querySelector(".suggestion-container");
        const gptModelNameField = document.getElementById("gpt_model_name");

        suggestionContainer.addEventListener("click", (e) => {
            if (e.target.classList.contains("suggestion")) {
                const suggestion = e.target.dataset.suggestion;
                gptModelNameField.value = suggestion;
            }
        });
    </script>
</body>
</html>