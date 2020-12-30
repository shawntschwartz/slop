<!-- Shawn T. Schwartz, 2020 -->
<!DOCTYPE html>
<html>
    <head>
        <script src="jsPsych/jspsych.js"></script>
        <script src="jsPsych/plugins/jspsych-image-keyboard-response.js"></script>
        <script src="jsPsych/plugins/jspsych-html-button-response.js"></script>
        <script src="jsPsych/plugins/jspsych-html-keyboard-response.js"></script>
        <script src="jsPsych/plugins/jspsych-instructions.js"></script>
        <link rel="stylesheet" href="jsPsych/css/jspsych.css">
        <style>
            img {
            width: 300px;
            }

            .wordgrid {
                table-layout: fixed !important; 
                white-space: nowrap !important;
            }

            .black {
                font-size: 50px; 
                font-weight: bold; 
                color: black; 
                text-transform: uppercase;
            }

            .colrd {
                font-size: 50px; 
                font-weight: bold; 
                color: red; 
                text-transform: uppercase;
            }

            .colbl {
                font-size: 50px; 
                font-weight: bold; 
                color: blue; 
                text-transform: uppercase;
            }

            #instructionTable td {
                text-align: center; 
                /* vertical-align: middle; */
            }
        </style>
    </head>

    <body>
    </body>

    <script>

        // CONSTANTS
        const LIVING = ["bird", "lamb", "tree", "cow", "plant", "dove", "horse", "owl", "fish", "bunny", "grass", "pig", "girl", "cat", "hawk", "frog", "adult", "queen", "nurse", "wife", "rat", "lice", "diver", "pet", "man", "kids", "woman", "wasp", "baby", "bride", "child", "beast", "dog", "puppy", "lion", "bees", "roach", "snake", "thief", "shark"];
        const NONLIVING = ["pizza", "ship", "cane", "bomb", "lamp", "tank", "bench", "quart", "bed", "key", "dirt", "door", "lake", "hat", "paint", "air", "bath", "book", "pie", "glass", "tool", "ship", "rock", "house", "candy", "flag", "radio", "wine", "lump", "ocean", "news", "beach", "mail", "debt", "gold", "wound", "plane", "car", "fire", "war"];
        const CONTROL = ["lazy", "quiet", "bored", "bland", "cozy", "slow", "pity", "shy", "rusty", "weary", "part", "safe", "wise", "blase", "phase", "tidy", "bless", "paint", "watch", "dump", "waste", "moody", "obey", "odd", "dark", "green", "nice", "blind", "cook", "kind", "dream", "pinch", "black", "rigid", "fall", "tune", "heal", "dirty", "kick", "foul", "gripe", "cut", "blond", "bake", "hard", "charm", "loyal", "cold", "greet", "rough", "learn", "good", "wink", "hope", "cute", "proud", "bold", "dead", "lost", "lie", "brave", "burn", "rude", "noisy", "toxic", "slap", "happy", "annoy", "tense", "lucky", "drown", "quick", "brutal", "power", "mad", "alert", "panic", "fight", "fun", "win"];
        const DEMO_LIVING = ["baby", "bear", "bird", "boar", "bush", "carp", "clam", "crab", "crow", "deer", "dove", "duck", "fawn", "fish", "foal", "frog", "gnat", "goat", "hare", "hawk", "lady", "lamb", "lion", "lynx", "mice", "mole", "moss", "moth", "mule", "newt", "pony", "puma", "seal", "slug", "swan", "toad", "tree", "wasp", "wolf", "worm"];
        const DEMO_NONLIVING = ["ball", "bath", "beer", "boat", "bowl", "camp", "coal", "coat", "cord", "dial", "disk", "drug", "farm", "film", "food", "fuel", "gift", "gold", "hill", "hole", "lake", "land", "loaf", "lock", "mast", "maze", "menu", "milk", "moon", "news", "path", "plug", "salt", "snow", "soil", "tank", "tool", "wall", "wine", "wood"];
        const BLOCK_LENGTH = 2; // Equal to 1 + number of desired trials per block (to account for final node)
        const BLOCKS = 1;
        
        var words_per_trial = CONTROL.length / (BLOCK_LENGTH - 1);

        // TRIAL BLOCKS
        var instructions_suppress = {
            type: 'instructions',
            pages: [
                '<p>In this task, a set of red and blue words will appear on the screen, like this:</p>' +
                "<br /><br /><center><table id='instructionTable' class='wordgrid'><col width='200px' /><col width='50px' /><col width='200px' /><tr><td><div class='colrd'>"+"rate"+"</div></td><td></td><td><div class='colbl'>"+"till"+"</div></td></tr><tr><td><div style='margin-bottom:80px;'>&nbsp;</div></td><td><div style='font-size:60px;'>+</div></td><td></td></tr><tr><td><div class='colbl'>"+"pool"+"</div></td><td></td><td><div class='colrd'>"+"tool"+"</div></td></tr></table></center>" +
                '<br /><br /><p>You need to remember all of the words and the color that they are written in.</p>',
                '<p>The word set will be followed by a cue telling you to keep remembering either the red or the blue words.</p><p>If the cue is REMEMBER READ, you should keep remembering the words "RATE" and "TOOL", because they were written in red in the word set before.</p><p>If the cue is REMEMBER BLUE, you should keep remembering the words "TILL" and "POOL", as they were written in blue in the word set before.</p>',
                '<p>After a pause, a test word (written in black ink) will appear:</p><br /><br /><h1>POOL</h1><br /><p>If the test word is one of the words you were supposed to attend to, press the Y key on your keyboard for <u>Y</u>es.</p><p>Otherwise, press the N key on your keyboard for <u>N</u>o.</p>',
                '<p>First, you will do some practice.</p><p>When you are ready to begin, press the <u>Next ></u> button below.</p><p>Otherwise, you may review the instructions using the <u>< Previous</u> button below.</p>'
                ],
            post_trial_gap: 2000,
            show_clickable_nav: true,
            allow_keys: false,
            data: {
                'trial_desc' : 'Instructions'
            }
        }

        var fixation_pre = {
            type: 'html-keyboard-response',
            stimulus: '<div style="font-size:60px;">+</div>',
            choices: jsPsych.NO_KEYS,
            trial_duration: 1000,
            data: {
                'trial_desc' : 'Pre-Fixation'
            }
        }

        var fixation_post = {
            type: 'html-keyboard-response',
            stimulus: '<div style="font-size:60px;">+</div>',
            choices: jsPsych.NO_KEYS,
            trial_duration: 3000,
            data: {
                'trial_desc' : 'Post-Fixation'
            }
        }
        
        var trials = [];
        var subject_id = jsPsych.randomization.randomID(15); // generate a random subject ID
        var trial_counter = 1;

        for(var block_itr = 0; block_itr < BLOCKS; block_itr++) {
            console.log("Block: " + block_itr+1);

            var cb_block_type = "deep"; // deep or shallow processing
            if(block_itr % 2 == 0) {
                cb_block_type == "shallow";
            }
            console.log(cb_block_type);

            // BUILD PROBE DISTRIBTUION
            var probe_list = [];
            var valid_distribution_total = Math.round((BLOCK_LENGTH-1) * .4);
            var lure_distribution_total = Math.round((BLOCK_LENGTH-1) * .3);
            var control_distribution_total = Math.round((BLOCK_LENGTH-1) * .3);

            // validate probe distribution
            if(valid_distribution_total + lure_distribution_total + control_distribution_total != (BLOCK_LENGTH-1)) {
                while(valid_distribution_total + lure_distribution_total + control_distribution_total != (BLOCK_LENGTH-1)) {
                    if(valid_distribution_total + lure_distribution_total + control_distribution_total > (BLOCK_LENGTH-1)) {
                        var random_delete_idx = jsPsych.randomization.sampleWithoutReplacement([0,1,2],1);
                        if(random_delete_idx == 0) { 
                            valid_distribution_total--; 
                        } else if(random_delete_idx == 1) { 
                            lure_distribution_total--; 
                        } else if(random_delete_idx == 2) { 
                            control_distribution_total--; 
                        }
                    } else if(valid_distribution_total + lure_distribution_total + control_distribution_total < (BLOCK_LENGTH-1)) {
                        var random_add_idx = jsPsych.randomization.sampleWithoutReplacement([0,1,2],1);
                        if(random_add_idx == 0) { 
                            valid_distribution_total++; 
                        } else if(random_add_idx == 1) {
                            lure_distribution_total++; 
                        } else if(random_add_idx == 2) { 
                            control_distribution_total++; 
                        }
                    }
                }
            }
            
            for(var i = 0; i < (valid_distribution_total + lure_distribution_total + control_distribution_total); i++) {
                var random_probe_idx = jsPsych.randomization.sampleWithoutReplacement([0,1,2],1);
                if(random_probe_idx == 0) { // Valid Probe Director
                    probe_list.push(0); 
                } else if(random_probe_idx == 1) { // Lure Probe Director
                    probe_list.push(1); 
                } else if(random_probe_idx == 2) { // Control Probe Director
                    probe_list.push(2); 
                }
            }
            console.log(probe_list);

            var cues = [];
            var LIVING_shuffled = jsPsych.randomization.shuffle(LIVING);
            var NONLIVING_shuffled = jsPsych.randomization.shuffle(NONLIVING);
            var CONTROL_shuffled = jsPsych.randomization.shuffle(CONTROL);
            var CONTROL_shuffled_COPY = [];
            // make deep copy of CONTROL_shuffled
            for(var i = 0; i < CONTROL_shuffled.length; i++) {
                CONTROL_shuffled_COPY.push(CONTROL_shuffled[i]);
            }

            console.log("CTRL_SHUFFLED: " + CONTROL_shuffled);

            if(cb_block_type == "deep") {
                while(LIVING_shuffled.length) {
                    cues.push(LIVING_shuffled.splice(0,2));
                    cues.push(NONLIVING_shuffled.splice(0,2));
                }
                console.log(cues); 
            } else if(cb_block_type == "shallow") {
                while(CONTROL_shuffled_COPY.length) {
                    cues.push(CONTROL_shuffled_COPY.splice(0,4));
                }
                console.log(cues);
            }
            
            var cue_type = [];
            for(var i = 0; i < BLOCK_LENGTH-1; i++) {
                cue_type.push(0); // living OR blue
                cue_type.push(1); // non-living OR red
            }
            var cue_type_shuffled = jsPsych.randomization.shuffle(cue_type);
            console.log("CUE TYPE: " + cue_type_shuffled);

            // var cues_pos = [];
            // for(var i = 0; i < BLOCK_LENGTH-1; i++) {
            //     cues_pos.push(jsPsych.randomization.sampleWithoutReplacement([0,1], 1)[0]);
            // }
            // cues_pos = jsPsych.randomization.shuffle(cues_pos);
            // console.log(cues_pos);

            var probe_words = [];
            var cue_iter = 0;
            console.log(cues.length);

            if(cb_block_type == "deep") {
                for(var jj = 0; jj < cues.length; jj += 2) {
                    if(cue_type_shuffled[cue_iter] == 0) { // living
                        if(probe_list[cue_iter] == 0) { // Valid Probe
                            probe_words.push(cues[jj][jsPsych.randomization.sampleWithoutReplacement([0,1], 1)[0]]);
                        } else if(probe_list[cue_iter] == 1) { // Lure Probe
                            probe_words.push(cues[jj+1][jsPsych.randomization.sampleWithoutReplacement([0,1], 1)[0]]);
                        } else if(probe_list[cue_iter] == 2) { // Control Probe
                            probe_words.push(jsPsych.randomization.sampleWithoutReplacement(CONTROL_shuffled, 1)[0]);
                        }
                    } else if(cue_type_shuffled[cue_iter] == 1) { // non-living
                        if(probe_list[cue_iter] == 0) { // Valid Probe
                            probe_words.push(cues[jj+1][jsPsych.randomization.sampleWithoutReplacement([0,1], 1)[0]]);
                        } else if(probe_list[cue_iter] == 1) { // Lure Probe
                            probe_words.push(cues[jj][jsPsych.randomization.sampleWithoutReplacement([0,1], 1)[0]]);
                        } else if(probe_list[cue_iter] == 2) { // Control Probe
                            probe_words.push(jsPsych.randomization.sampleWithoutReplacement(CONTROL_shuffled, 1)[0]);
                        }
                    }
                    cue_iter++;
                }

                console.log(probe_words);
                console.log(cues);

                for(var i = 0; i < BLOCK_LENGTH-1; i+=2) {
                    // 1. MEMORY SET (4500 ms)
                    trials.push({ 
                        stimulus: "<table class='wordgrid'><col width='200px' /><col width='50px' /><col width='200px' /><tr><td><div class='black'>"+cues[i][0]+"</div></td><td></td><td><div class='black'>"+cues[i+1][0]+"</div></td></tr><tr><td><div style='margin-bottom:80px;'>&nbsp;</div></td><td><div style='font-size:60px;'>+</div></td><td></td></tr><tr><td><div class='black'>"+cues[i+1][1]+"</div></td><td></td><td><div class='black'>"+cues[i][1]+"</div></td></tr></table>", data: { 'animate_word_1' : cues[i][0], 'animate_word_2' : cues[i][1], 'inanimate_word_1' : cues[i+1][0], 'inanimate_word_2' : cues[i+1][1], 'list' : trial_counter, 'trial_desc' : 'MemorySet' }, choices: jsPsych.NO_KEYS, trial_duration: 4500
                    });

                    // 2. FIXATION (1000 ms)
                    trials.push(fixation_pre);

                    // 3. INSTRUCTION CUE (1000 ms)
                    if(cue_type_shuffled[i] == 0) { // living
                        trials.push({
                            stimulus: '<h1>REMEMBER LIVING</h1>', data: { 'cue_type' : 'Remember', 'cue_value' : "Animate", 'list' : trial_counter, 'trial_desc' : 'InstructionCue' }, choices: jsPsych.NO_KEYS, trial_duration: 1000
                        });
                    } else if(cue_type_shuffled[i] == 1) { // non-living
                        trials.push({
                            stimulus: '<h1>REMEMBER NON-LIVING</h1>', data: { 'cue_type' : 'Remember', 'cue_value' : 'Inanimate', 'list' : trial_counter, 'trial_desc' : 'InstructionCue' }, choices: jsPsych.NO_KEYS, trial_duration: 1000
                        });
                    }

                    // 4. FIXATION (3000 ms)
                    trials.push(fixation_post);

                    // 5. PROBE
                    if(probe_list[i] == 0) { // VALID (40% freq.)
                        trials.push({
                            stimulus: "<h1 style='text-transform: uppercase;'>" + probe_words[i] + "</h1>", trial_duration: 10000, choices: [89, 78], data: { 'list' : trial_counter, 'trial_desc' : 'Probe', 'probe_word' : probe_words[i], 'probe_type' : 'Valid' }, on_finish: function(data){if(data.key_press == 89){data.correct = true;}else if(data.key_press == null){data.correct = "TIMEOUT";}else{data.correct = false;}}
                        });
                    } else if (probe_list[i] == 1) { // LURE (30% freq.)
                        trials.push({
                            stimulus: "<h1 style='text-transform: uppercase;'>" + probe_words[i] + "</h1>", trial_duration: 10000, choices: [89, 78], data: { 'list' : trial_counter, 'trial_desc' : 'Probe', 'probe_word' : probe_words[i], 'probe_type' : 'Lure' }, on_finish: function(data){if(data.key_press == 78){data.correct = true;}else if(data.key_press == null){data.correct = "TIMEOUT";}else{data.correct = false;}}
                        });
                    } else if (probe_list[i] == 2) { // CONTROL (30% freq.)
                        trials.push({
                            stimulus: "<h1 style='text-transform: uppercase;'>" + probe_words[i] + "</h1>", trial_duration: 10000, choices: [89, 78], data: { 'list' : trial_counter, 'trial_desc' : 'Probe', 'probe_word' : probe_words[i], 'probe_type' : 'Control' }, on_finish: function(data){if(data.key_press == 78){data.correct = true;}else if(data.key_press == null){data.correct = "TIMEOUT";}else{data.correct = false;}}
                        });
                    }

                    trial_counter++;
                }
            } else if(cb_block_type == "shallow") {
                for(var jj = 0; jj < cues.length; jj++) {
                    if(cue_type_shuffled[cue_iter] == 0) { // blue
                        if(probe_list[cue_iter] == 0) { // Valid Probe
                            var pooled_valid_probe = cues[jj][jsPsych.randomization.sampleWithoutReplacement([0,1], 1)[0]];
                            probe_words.push(pooled_valid_probe);
                        } else if(probe_list[cue_iter] == 1) { // Lure Probe
                            var pooled_lure_probe = cues[jj][jsPsych.randomization.sampleWithoutReplacement([2,3], 1)[0]];
                            probe_words.push(pooled_lure_probe);
                        } else if(probe_list[cue_iter] == 2) { // Control Probe
                            var rnd_sampled_ctrl_word = jsPsych.randomization.sampleWithoutReplacement(CONTROL_shuffled, 1)[0];
                            if(cues[jj].includes(rnd_sampled_ctrl_word)) {
                                while(cues[jj].includes(rnd_sampled_ctrl_word)) {
                                    rnd_sampled_ctrl_word = jsPsych.randomization.sampleWithoutReplacement(CONTROL_shuffled, 1)[0];
                                    console.log("redrawing rnd_sampled_ctrl_word (BLUE)...");
                                }
                            }
                            probe_words.push(rnd_sampled_ctrl_word);
                        }
                    } else if(cue_type_shuffled[cue_iter] == 1) { // red
                        if(probe_list[cue_iter] == 0) { // Valid Probe
                            var pooled_valid_probe = cues[jj][jsPsych.randomization.sampleWithoutReplacement([2,3], 1)[0]];
                            probe_words.push(pooled_valid_probe);
                        } else if(probe_list[cue_iter] == 1) { // Lure Probe
                            var pooled_lure_probe = cues[jj][jsPsych.randomization.sampleWithoutReplacement([0,1], 1)[0]];
                            probe_words.push(pooled_lure_probe);
                        } else if(probe_list[cue_iter] == 2) { // Control Probe
                            var rnd_sampled_ctrl_word = jsPsych.randomization.sampleWithoutReplacement(CONTROL_shuffled, 1)[0];
                            if(cues[jj].includes(rnd_sampled_ctrl_word)) {
                                while(cues[jj].includes(rnd_sampled_ctrl_word)) {
                                    rnd_sampled_ctrl_word = jsPsych.randomization.sampleWithoutReplacement(CONTROL_shuffled, 1)[0];
                                    console.log("redrawing rnd_sampled_ctrl_word (RED)...");
                                }
                            }
                            probe_words.push(rnd_sampled_ctrl_word);
                        }
                    }
                    cue_iter++;
                }

                console.log(probe_words);
                console.log(cues);

                for(var i = 0; i < BLOCK_LENGTH-1; i++) {
                    // 1. MEMORY SET (4500 ms)
                    trials.push({
                        stimulus: "<table class='wordgrid'><col width='200px' /><col width='50px' /><col width='200px' /><tr><td><div class='colbl'>"+cues[i][0]+"</div></td><td></td><td><div class='colrd'>"+cues[i][2]+"</div></td></tr><tr><td><div style='margin-bottom:80px;'>&nbsp;</div></td><td><div style='font-size:60px;'>+</div></td><td></td></tr><tr><td><div class='colrd'>"+cues[i][3]+"</div></td><td></td><td><div class='colbl'>"+cues[i][1]+"</div></td></tr></table>", data: { 'blue_word_1' : cues[i][0], 'blue_word_2' : cues[i][1], 'red_word_1' : cues[i][2], 'red_word_2' : cues[i][3], 'list' : trial_counter, 'trial_desc' : 'MemorySet' }, choices: jsPsych.NO_KEYS, trial_duration: 4500
                    });

                    // 2. FIXATION (1000 ms)
                    trials.push(fixation_pre); 

                    // 3. INSTRUCTION CUE (1000 ms)
                    if(cue_type_shuffled[i] == 0) { // blue
                        trials.push({
                            stimulus: '<h1>REMEMBER BLUE</h1>', data: { 'cue_type' : 'Remember', 'cue_value' : 'Blue', 'list' : trial_counter, 'trial_desc' : 'InstructionCue' }, choices: jsPsych.NO_KEYS, trial_duration: 1000
                        });
                    } else if(cue_type_shuffled[i] == 1) { // red
                        trials.push({
                            stimulus: '<h1>REMEMBER RED</h1>', data: { 'cue_type' : 'Remember', 'cue_value' : 'Red', 'list' : trial_counter, 'trial_desc' : 'InstructionCue' }, choices: jsPsych.NO_KEYS, trial_duration: 1000
                        });
                    }

                    // 4. FIXATION (3000 ms)
                    trials.push(fixation_post);

                    // 5. PROBE
                    if(probe_list[i] == 0) { // VALID (40% freq.)
                        trials.push({
                            stimulus: "<h1 style='text-transform: uppercase;'>" + probe_words[i] + "</h1>", trial_duration: 10000, choices: [89, 78], data: { 'list' : trial_counter, 'trial_desc' : 'Probe', 'probe_word' : probe_words[i], 'probe_type' : 'Valid' }, on_finish: function(data){if(data.key_press == 89){data.correct = true;}else if(data.key_press == null){data.correct = "TIMEOUT";}else{data.correct = false;}}
                        });
                    } else if (probe_list[i] == 1) { // LURE (30% freq.)
                        trials.push({
                            stimulus: "<h1 style='text-transform: uppercase;'>" + probe_words[i] + "</h1>", trial_duration: 10000, choices: [89, 78], data: { 'list' : trial_counter, 'trial_desc' : 'Probe', 'probe_word' : probe_words[i], 'probe_type' : 'Lure' }, on_finish: function(data){if(data.key_press == 78){data.correct = true;}else if(data.key_press == null){data.correct = "TIMEOUT";}else{data.correct = false;}}
                        });
                    } else if (probe_list[i] == 2) { // CONTROL (30% freq.)
                        trials.push({
                            stimulus: "<h1 style='text-transform: uppercase;'>" + probe_words[i] + "</h1>", trial_duration: 10000, choices: [89, 78], data: { 'list' : trial_counter, 'trial_desc' : 'Probe', 'probe_word' : probe_words[i], 'probe_type' : 'Control' }, on_finish: function(data){if(data.key_press == 78){data.correct = true;}else if(data.key_press == null){data.correct = "TIMEOUT";}else{data.correct = false;}}
                        });
                    }

                    trial_counter++;
                }
            }

        }

        var block = {
            type: 'html-keyboard-response',
            choice: jsPsych.NO_KEYS,
            on_finish: function(data) {
                if(data.key_press == 78) {
                    jsPsych.endCurrentTimeline();
                }
            },
            timeline: trials
        }

        var after_block = {
            type: 'html-keyboard-response',
            stimulus: '<p>Done..this is where I will save the data....<strong>Press <em>spacebar</em> to write data to server.</strong></p>',
            is_html: true
        }

        var condition_assignment = "suppress";

        jsPsych.data.addProperties({
            sessionID: subject_id,
            conditionID: condition_assignment
        });

        var file_pattern = condition_assignment + "_" + subject_id + "_" + "dataoutput";

        function saveData(filename, filedata) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_data.php');
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({filename: file_pattern, filedata: filedata}));        
        };

        jsPsych.init({
            timeline: [block, after_block],
            on_finish: function() {
                jsPsych.data.displayData();
                var all_data = jsPsych.data.get();
                console.log(all_data.csv());
                saveData(file_pattern, all_data.csv());
                console.log("Data saved at: " + file_pattern);
            }
        });
        
    </script>
</html>