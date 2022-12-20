<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vite App</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div>
    <div> Hello world </div>

    <div>
        <div> Streaming </div>
        <div id="streamingResponse"></div>
    </div>

    <div class="mt-12">
        <div> SSE </div>
        <div id="sseResponse"></div>
    </div>

    <script>
        function testXHR() {
            let lastResponseLength = false;

            xhr = new XMLHttpRequest();

            xhr.open("GET", "/processes/mock", true);

            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').content);
            xhr.onprogress = function(e) {
                let progressResponse;
                let response = e.currentTarget.response;

                progressResponse = lastResponseLength ?
                    response.substring(lastResponseLength)
                    : response;

                lastResponseLength = response.length;
                let parsedResponse = JSON.parse(progressResponse);
                document.getElementById('streamingResponse').innerHTML += parsedResponse.data
                console.log(parsedResponse)


                if(Object.prototype.hasOwnProperty.call(parsedResponse, 'success')) {
                    console.log('success');
                }
            }
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && this.status == 200) {
                    console.log("Complete = " + xhr.responseText);
                }
            }

            xhr.send();
        }
        // testXHR()

        function sse() {
            const evtSource = new EventSource("/processes/sse");
            evtSource.onmessage = function(e) {
                console.log(e.data);
                document.getElementById('sseResponse').innerHTML += e.data + '<br>'
            };
            evtSource.onerror = (err) => {
                console.error("EventSource failed:", err);
            };

        }
        sse()


    </script>
</div>
</body>
</html>
