
@if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    <script src="../assets/bundles/jquerymaskedinput.bundle.js"></script>

@else
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    <script src="../assets/bundles/jquerymaskedinput.bundle.js"></script>

@endif
