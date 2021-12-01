<input type="password" id="password">
<button onclick="doVerify()">送出</button>
<script>
    function doVerify(){
        var el = document.getElementById('password');
        password = el.value;
        fetch("/apidoc/verifydoc", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                password: password
            })
        }).then((response) => {
            if (!response.ok) throw new Error(response.statusText);
            return response; 
        }).then((response) => {
            window.location.reload();
        }).catch((err) => {
            alert("錯誤");
        })
    }
</script>