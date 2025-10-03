function musicgen(event){
    event.preventDefault(); 

    const description = document.getElementById('My_description').value;
    const duration = document.getElementById('duration').value;
    const status = document.getElementById('status');
    const audioPlayer = document.getElementById('audioPlayer');
    const downloadLink = document.getElementById('downloadLink');
    const descriptionAudio = document.getElementById('description_audio');

    status.textContent = "Generating music... please wait."; 
    audioPlayer.style.display = 'none'; 
    downloadLink.style.display = 'none'; 

    fetch('http://localhost:5000/generate_music', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            description: description,
            duration: parseInt(duration)
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("HTTP error, status = " + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.audio_base64) {
            const audioSrc = `data:audio/mpeg;base64,${data.audio_base64}`;
            audioPlayer.src = audioSrc; 
            audioPlayer.style.display = 'block'; 
        
            downloadLink.href = audioSrc; 
            downloadLink.style.display = 'block'; 
            downloadLink.textContent = 'Download Generated Music';
            
            const textMuted = document.querySelector('.text-muted');
            textMuted.innerHTML = `<strong>Description:</strong> ${description}`;
            
            // **อัปเดตค่า input hidden**
            descriptionAudio.value = description;
            
            status.textContent = "Music generated successfully!"; 
        } else {
            status.textContent = "Error generating music."; 
        }
    })
    .catch(error => {
        status.textContent = "An error occurred: " + error; 
    });
}

const startButton = document.getElementById('startButton');
startButton.addEventListener('click', musicgen);
