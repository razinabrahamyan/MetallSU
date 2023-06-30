<img src="data:image/png;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size('250')->generate($url)) !!} ">
<script>
    window.print();
</script>
