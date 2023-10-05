const onLoad = () => {


    //console.log(inputComment);
    //console.log(inputEmail);

    document.addEventListener('submit', event => {
        event.preventDefault();
        const formComment = document.querySelector("#form-comment");

        const inputComment = document.getElementById('comment').value;
        const inputEmail = document.getElementById('email').value;
        const dovetailingId = document.getElementById('dovetailing-id').value;

        if (formComment) {
            //console.log(formComment);
            const data = new URLSearchParams();
            for (const pair of new FormData(formComment)) {
                data.append(pair[0], pair[1]);
            }
            console.log(Array.from(data));

            try {
                fetch(
                    '/comment/new/' + dovetailingId,
                    {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: data,
                    }
                )
                .then(function(response) {
                    if (response.ok) {
                        return response.text();
                    } else {
                        console.log('Request failed!');
                        return false;
                    }
                })
                .then(function(data) {
                    if(data)
                    {
                        //console.log(data);
                        window.location.href = "/dovetailing/" + dovetailingId;
                    }
                    else {
                        console.log("rien");
                    }
                });
                // const resData = await res;
                // console.log(resData);
            } catch (err) {
                console.log(err.message);
            }
        }
    });


    // pattern=".+@globex\.com"
}
window.addEventListener('load', onLoad );

function validation(event, inputEmail, inputComment) {

    console.log('clicked submit !');
    if (inputEmail && inputComment) {
        var patternEmail = new RegExp('^[\\w-\.]+@([\\w-]+\\.)+[\w-]{2,4}$');
        if (!patternEmail.test(inputEmail.value) || inputComment.value === '') {
            console.log('validations errors...');
            event.preventDefault();

            if (!pattern.test(inputEmail.value)) {
                console.log("bad email !");
                let divErrorMail = document.createElement('div');
                divErrorMail.textContent = 'Please enter a valid email !';
                divErrorMail.className = 'text-danger';
                inputEmail.before(divErrorMail);
            }

            if (inputComment.value === '') {
                let divErrorComment = document.createElement('div');
                divErrorComment.textContent = 'Please enter a comment !';
                divErrorComment.className = 'text-danger';
                inputComment.before(divErrorComment);
            }
        } else {
            return true;
        }
    }
}