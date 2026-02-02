<?php
    $force_preprocessing_virus_scan = true; // if true, all uploads are virus scanned before any other checks are done
    $set_approved_status_to_true_default = false; // if true, all uploads by non-admin users do not require approval by an admin user
    $force_upload_limiter = false; // if true, if a non-admin user has more than 6 uploads, each additional upload will have a minimum 50% chance of failure
    $force_efficient_file_size = false; // if true, uploads are limited to 1gb in size and 20 minutes in length
    $force_16_9_mp4_format = false; // if true, uploads must be in mp4 format, and with a 16:9 or 9:16 aspect ratio
?>