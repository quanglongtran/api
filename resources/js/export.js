import Swal from "sweetalert2";
import Jquery from "jquery";
import { initializeApp } from "firebase/app";
import { getMessaging, onMessage, getToken } from "firebase/messaging";

const firebaseConfig = {
    apiKey: "AIzaSyBmvbpMKojkSw6sTR4e1ey-ujBNeZjDF-E",
    authDomain: "push-notification-76893.firebaseapp.com",
    projectId: "push-notification-76893",
    storageBucket: "push-notification-76893.appspot.com",
    messagingSenderId: "936638378635",
    appId: "1:936638378635:web:f04ab251fe2d87a5c11a8e"
};
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);
const vapidKey = 'BGUAOB1It5FH6tJpXxEdhC4kA_TYboXNRC0T_kWiRNokAWJDCxHWg0xui0kd64Db9ulAksNK03oz-S9sW__GMy8';

const $ = Jquery;

$('a[href="#"]').on('click', function (e) {
    e.preventDefault();
});

$('[disabled]').on('click', function (e) {
    e.preventDefault();
});

$('[disabled]').css({
    filter: 'grayscale(100%)',
    cursor: 'default',
    pointerEvents: 'none',
    boxShadow: 'unset',
});

$('[disabled]').hover(function () {
    $(this).css({
        backgroundColor: '#9A9A9A',
    });
})

onMessage(messaging, (payload) => {
    console.log('Message received. ', payload);
    // ...
});

$('#getAgentToken-btn').on('click', function () {
    getToken(messaging, { vapidKey: vapidKey }).then((currentToken) => {
        if (currentToken) {
            $.ajax({
                url: 'http://127.0.0.1:8000/api/notify/update',
                headers: {
                    Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE2NjI1NTMwMTUsImV4cCI6MTY2MjU1NjYxNSwibmJmIjoxNjYyNTUzMDE1LCJqdGkiOiJudEdhdWtlcnIwRGFiaXJQIiwic3ViIjoiNTEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.nnd0_rpPCyhZWTtUCWXdkZfK091UR2_D4IXCviovzw8'
                },
                type: 'POST',
                data: {
                    device_token: currentToken
                }
            });
        } else {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '',
                text: 'Vui lòng bật thông báo.',
                showConfirmButton: false,
                timer: 1500
            })
        }
    }).catch(error => {
        Swal.fire({
            position: 'center',
            title: 'Vui lòng bật thông báo',
            icon: 'error'
        });
    });
});