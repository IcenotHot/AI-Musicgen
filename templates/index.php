<?php
    session_start();
    include ('../config.php');
    
    if (empty($_SESSION[WP.'checklogin']))  {
        $_SESSION['message']='You are not autherlize';
        header("location:{$base_url}/login.php");
        exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include '../include/menu.php';?>
    <?php if(!empty($_SESSION['massge'])):?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['massge']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['massge']); ?>
    <?php endif ; ?>
  
    <div class="container " style = "margin-top: 30px;" >
        <form id="musicForm" enctype="multipart/form-data" action="<?php echo $base_url.'/song-form.php'; ?>" method="POST">
            <div class="mb-3">
                <label class="form-label">Prompt Wizard</label>
                <select class="form-select" id="musicPrompt" name="musicPrompt" class="form-control" onchange="fillDescription()">
                    <option selected></option>
                    <option value="Classic Rock: Soaring electric guitar solos, powerful drum fills, driving bassline, atmospheric organ.">Classic Rock: Soaring electric guitar solos, powerful drum fills, driving bassline, atmospheric organ.</option>
                    <option value="Jazz: Smooth saxophone melodies, walking double bass, brushed snare drums, subtle piano chords.">Jazz: Smooth saxophone melodies, walking double bass, brushed snare drums, subtle piano chords..</option>
                    <option value="Hip-Hop: Punchy beats, rhythmic vocal flow, deep sub-bass, vinyl crackle textures.">Hip-Hop: Punchy beats, rhythmic vocal flow, deep sub-bass, vinyl crackle textures.</option>
                    <option value="Lo-fi Chillhop: Warm vinyl hiss, mellow guitar loops, jazzy chord progressions, laid-back drums.">Lo-fi Chillhop: Warm vinyl hiss, mellow guitar loops, jazzy chord progressions, laid-back drums.</option>
                    <option value="Pop: Catchy hooks, layered synths, bright vocals, upbeat drum patterns.">Pop: Catchy hooks, layered synths, bright vocals, upbeat drum patterns.</option>
                    <option value="EDM (Electronic Dance Music): Thumping kick drums, shimmering synth leads, heavy drops, rhythmic sidechaining.">EDM (Electronic Dance Music): Thumping kick drums, shimmering synth leads, heavy drops, rhythmic sidechaining.</option>
                    <option value="Trap: Crisp hi-hats, booming 808s, sparse melodies, aggressive vocal delivery.">Trap: Crisp hi-hats, booming 808s, sparse melodies, aggressive vocal delivery.</option>
                    <option value="Funk: Groovy basslines, tight rhythm guitar, horn stabs, syncopated drum beats.">Funk: Groovy basslines, tight rhythm guitar, horn stabs, syncopated drum beats.</option>
                    <option value="Reggae: Off-beat guitar chops, relaxed bass grooves, syncopated drums, laid-back vocals.">Reggae: Off-beat guitar chops, relaxed bass grooves, syncopated drums, laid-back vocals.</option>
                    <option value="R&B: Smooth vocal runs, soulful harmonies, lush synth pads, steady grooves.">R&B: Smooth vocal runs, soulful harmonies, lush synth pads, steady grooves.</option>
                    <option value="Country: Twangy acoustic guitars, slide guitar licks, storytelling lyrics, steady snare brushes.">Country: Twangy acoustic guitars, slide guitar licks, storytelling lyrics, steady snare brushes.</option>
                    <option value="Blues: Expressive guitar bends, slow shuffle rhythm, soulful vocals, harmonica flourishes.">Blues: Expressive guitar bends, slow shuffle rhythm, soulful vocals, harmonica flourishes.</option>
                    <option value="Punk Rock: Fast-paced power chords, raw vocals, aggressive drumming, DIY aesthetic.">Punk Rock: Fast-paced power chords, raw vocals, aggressive drumming, DIY aesthetic.</option>
                    <option value="Indie Pop: Dreamy synth textures, jangly guitars, heartfelt lyrics, lo-fi percussion.">Indie Pop: Dreamy synth textures, jangly guitars, heartfelt lyrics, lo-fi percussion.</option>
                    <option value="Ambient: Soft pads, spacious textures, slow-evolving drones, minimal melodic movement.">Ambient: Soft pads, spacious textures, slow-evolving drones, minimal melodic movement.</option>
                    <option value="Techno: Repetitive kick drum, hypnotic synth loops, industrial textures, progressive layering.">Techno: Repetitive kick drum, hypnotic synth loops, industrial textures, progressive layering.</option>
                    <option value="House: Four-on-the-floor beats, groovy basslines, chopped vocal samples, funky synth stabs.">House: Four-on-the-floor beats, groovy basslines, chopped vocal samples, funky synth stabs.</option>
                    <option value="Synthwave: Retro synth leads, driving arpeggios, gated reverb snares, nostalgic vibes.">Synthwave: Retro synth leads, driving arpeggios, gated reverb snares, nostalgic vibes.</option>
                    <option value="Orchestral: Lush string arrangements, bold brass swells, dramatic timpani, cinematic dynamics.">Orchestral: Lush string arrangements, bold brass swells, dramatic timpani, cinematic dynamics.</option>
                    <option value="Latin Pop: Catchy melodies, rhythmic percussion (congas, bongos), upbeat tempos, passionate vocals.">Latin Pop: Catchy melodies, rhythmic percussion (congas, bongos), upbeat tempos, passionate vocals.</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">เเนวเพลง</label>
                <select class="form-select" id="music" name="music" class="form-control" onchange="fillDescription()">
                    <option selected></option>
                    <option value="Classic Rock">Classic Rock</option>
                    <option value="80s Rock">80s Rock: </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tempo(แนวเพลง)</label>
                <select class="form-select" id="rhythm" name="rhythm" class="form-control" onchange="fillDescription()">
                    <option selected></option>
                    <option value="Fast">Fast</option>
                    <option value="Medium">Medium</option>
                    <option value="Slow">Slow</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Instrument(เครื่องดนตรี)</label>
                <select class="form-select" id="instrument" name="instrument" class="form-control" onchange="fillDescription()">
                    <option selected></option>
                    <option value="Electric Guitar">Electric Guitar</option>
                    <option value="Drums">Drums</option>
                    <option value="Bass">Bass</option>
                    <option value="Organ">Organ</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description">Enter music description:</label>
                <textarea class="form-control" id="My_description" rows="3"></textarea>
            </div>
            <label for="duration">Select time duration (in seconds):</label>
            <input type="range" id="duration" name="duration" min="0" max="20" value="10" oninput="this.nextElementSibling.value = this.value">
            <output>10</output>
            <script>
            function fillDescription() {
            const music = document.getElementById("music").value.trim();
            const rhythm = document.getElementById("rhythm").value.trim();
            const instrument = document.getElementById("instrument").value.trim();
            const prompt = document.getElementById("musicPrompt").value.trim();
            const description = document.getElementById("My_description");

          
            let combinedText = [music, rhythm, instrument, prompt].filter(Boolean).join(" ");
            
            
            description.value = combinedText;
                 }
            </script>
            <button type="button" id="startButton"  class="btn btn-primary w-100">Start</button>
            
            <p id="status"></p>
            <table class = "table table-bordered border-info">
                <thead>
                    <tr>
                        <th style="width: 100px;">Song</th>
                        <th style="width: 200px;">Prompt</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <td>
                    <audio type="file" id="audioPlayer" name = "my_audio" controls style="display: none;"></audio>
                    <a id="downloadLink" href="#" style="display: none;">Download Music</a>
                </td>
                <td>
                    <div>
                        <small class = "text-muted" id="TextDisplay"></small>
                        <input type="hidden" name="description_audio" id="description_audio" class = "text-muted">
                        
                    </div>
                </td>
                <td>
                    <input type="hidden" name="audio_filename" value="audio_output.wav">
                    <button type="submit" name="submit" id = "saveButton"class="btn btn-outline-dark">Save</button>
                    <a onclick ="return confirm('Are you sure you want to delete');" role = "button" href = "" class ="btn btn-outline-danger">Delete</a>
                </td>
            </table>  
        </div>
        </form>  
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.getElementById("TextInput").value = document.getElementById("TextDisplay").innerText;
    </script>
    <script src="js-api.js"></script>
    
    
</body>
</html>