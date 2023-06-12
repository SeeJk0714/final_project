<?php
    unset( $_SESSION['user'] );
    header("Location: /");
    exit;