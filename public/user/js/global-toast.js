(function(){

    function escapeHTML(str){
        if(!str) return '';
        return String(str).replace(/[&<>"]/g,function(c){
            return {
                '&':'&amp;',
                '<':'&lt;',
                '>':'&gt;',
                '"':'&quot;'
            }[c] || c;
        });
    }

    window.showToast = function(message,type='success'){

        const toast = document.createElement('div');

        toast.className = `luxury-toast luxury-toast-${type}`;

        toast.innerHTML = `
            <i class="fas fa-${
                type==='success'
                ? 'check-circle'
                : type==='error'
                ? 'times-circle'
                : type==='warning'
                ? 'exclamation-triangle'
                : 'info-circle'
            } me-2"></i>
            <span>${escapeHTML(message)}</span>
        `;

        document.body.appendChild(toast);

        setTimeout(()=>toast.classList.add('show'),10);

        setTimeout(()=>{
            toast.classList.remove('show');
            setTimeout(()=>toast.remove(),300);
        },3000);
    };

    if(!document.querySelector('#toast-styles')){

        const style = document.createElement('style');

        style.id='toast-styles';

        style.textContent=`
            .luxury-toast{
                position:fixed;
                bottom:30px;
                right:30px;
                background:white;
                padding:15px 25px;
                border-radius:10px;
                box-shadow:0 8px 30px rgba(0,0,0,0.15);
                display:flex;
                align-items:center;
                z-index:9999;
                transform:translateY(100px);
                opacity:0;
                transition:all .3s ease;
                border-left:4px solid;
                min-width:300px;
            }

            .luxury-toast.show{
                transform:translateY(0);
                opacity:1;
            }

            .luxury-toast-success{border-left-color:#81c408;}
            .luxury-toast-success i{color:#81c408;}

            .luxury-toast-info{border-left-color:#ff9800;}
            .luxury-toast-info i{color:#ff9800;}

            .luxury-toast-error{border-left-color:#e74c3c;}
            .luxury-toast-error i{color:#e74c3c;}

            .luxury-toast-warning{border-left-color:#f39c12;}
            .luxury-toast-warning i{color:#f39c12;}

            @media(max-width:768px){
                .luxury-toast{
                    left:20px;
                    right:20px;
                    bottom:20px;
                    min-width:auto;
                }
            }
        `;

        document.head.appendChild(style);
    }

})();
