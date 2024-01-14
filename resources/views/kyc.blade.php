<html>

<head>
    <script type="text/javascript">
        const url = "{{ $url['data']['url'] }}"

        function loadFormPopup(url) {
            let params =
                `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=0,height=0,left=100,top=100`;
            let popup = window.open(url, "KYC", params);
            setTimeout(function() {
                popup.close();
            }, 60000);
        }

        function loadFormNewTab(url) {
            let popup = window.open(url, "_blank");
            setTimeout(function() {
                popup.close();
            }, 60000);
        }
    </script>
</head>

<body>
    <button onclick="loadFormPopup()">KYC Pasien Popup</button>
    <button onclick="loadFormNewTab()">KYC Pasien New Tab</button>
</body>

</html>
