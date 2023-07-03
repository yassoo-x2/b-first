$(function () {
    'use strict';
    var url = new URLSearchParams(window.location.search);

    //-------------------regestered user
    if (url.has('alreadyreg')) {
        window.onload = function() {
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: false,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
        Toast.fire({
            icon: 'error',
            title: 'Email , Phone or user Name was Registared befor'
        })
    };
}


    //--------------------------- No Balance

    if (url.has('nobalance')) {
        window.onload = function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: false,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        Toast.fire({
            icon: 'warning',
            title: 'Please recharg',
            text: 'your balance lower than cost of number'
        })
        }
    }

        //-------------------nonumber
        if (url.has('nonumber')) {
            window.onload = function() {
            const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: false,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
            Toast.fire({
                icon: 'warning',
                title: 'No number,pleas try again later'
            })
        };
    }

















})