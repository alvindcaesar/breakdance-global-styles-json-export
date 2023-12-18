<?php ?>
<h2>Export / Import Global Styles</h2>

<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row">
                Export
            </th>
            <td>
            <form action="<?php echo admin_url('admin-post.php')?>" method="post">
                <input type="hidden" name="action" value="bd_export_settings">
                <input type="hidden" name="data" value="exportSettingsid">
                <input type="submit" class ="button-secondary" value="Export Global Styles">
            </form>
                <p class="description" style='margin-top: 25px;'>Export this site's global styles as a JSON file that can be shared or used to restore your Breakdance global styles on another site.</p>   
            </td>
        </tr>

        <tr>
            <th scope="row">
                Import
            </th>
            <td>
            <form method="post" enctype="multipart/form-data" action="<?php echo admin_url( 'admin-post.php' ); ?>" >
                <input type="hidden" name="action" value="bd_import_settings">
                <input type="file" name="json_file" id="json_file" onchange="checkFile()">
                <?php wp_nonce_field( BD_IMPORT_SETTINGS_NONCE ); ?>
                <button id="submitBtn" type="submit" class="button-secondary" disabled>Import Global Styles</button>
            </form>
            <p class="description" style='margin-top: 25px;'>To import your Breakdance Global Styles from a JSON file, follow these steps:</p>
                <ol>
                    <li>Click on the "Browse" button.</li>
                    <li>Select the JSON file containing your Breakdance global styles that you want to import.</li>
                    <li>Click on the "Import Global Styles" button to initiate the import process.</li>
                    <li>Once the import is complete, you will be redirected to <b>Global Settings</b> page. Hit 'Save' to apply the styles.</li>
                </ol>
            <p>Please note that importing settings from a JSON file will overwrite your current Breakdance global styles, so make sure to back up your current global styles before importing.</p>   
            </td>
        </tr>
    </tbody>
</table>

<script>
    function checkFile() {
        var fileInput = document.getElementById('json_file');
        var submitButton = document.getElementById('submitBtn');

        if (fileInput.files.length > 0) {
            var fileName = fileInput.files[0].name;
            var fileExtension = fileName.split('.').pop().toLowerCase();

            if (fileExtension === 'json') {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
                alert('Please choose a JSON file.');
            }
        } else {
            submitButton.disabled = true;
        }
    }
</script>