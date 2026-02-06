<?php
    $force_preprocessing_virus_scan = true; // if true, all uploads are virus scanned before any other checks are done
    $set_approved_status_to_true_default = true; // if true, all uploads by non-admin users do not require approval by an admin user
    $force_upload_limiter = false; // if true, if a non-admin user has more than 6 uploads, each additional upload will have a minimum 50% chance of failure
    $force_efficient_file_size = true; // if true, uploads are limited to 1gb in size and 20 minutes in length -> change max values below
    $force_16_9_mp4_format = true; // if true, uploads must be in mp4 format, and with a 16:9 or 9:16 aspect ratio
    $force_cpu_usage_state = true; // if true, uploads cannot be processed if cpu use is above 70%

    $enforced_max_file_size = 1000*1000*1500; // in bytes, units of 1000
    $enforced_max_video_length = 60*15; // in seconds
    $enforced_video_aspect_ratio = "16:9";
    $enforced_video_aspect_ratio_alt = "9:16";
    $enforced_video_file_ext = "MPEG-4";
    $enforced_cpu_use_limit = 70.0;
?>