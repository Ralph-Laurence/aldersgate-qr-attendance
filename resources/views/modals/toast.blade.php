@push('styles')
    <style>
        .toast-stack-frame 
        {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .toast 
        {
            overflow: hidden;
            width: 350px;
            opacity: 0;
            margin-top: 0;
            -webkit-transition: opacity .25s ease-in-out, margin-top .3s linear;
            -moz-transition: opacity .25s ease-in-out, margin-top .3s linear;
            -ms-transition: opacity .25s ease-in-out, margin-top .3s linear;
            -o-transition: opacity .25s ease-in-out, margin-top .3s linear;
            transition: opacity .25s ease-in-out, margin-top .3s linear;
        }

        .toast, .toast-header, .toast-body
        {
            background-color: #262B4A;
            /* background-color: var(--text-color-700); */
            color: var(--page-background);
        }
         
        .toast .toast-header {
            padding: 8px 12px;
        }
        
        .toast-header .toast-title {
            border-radius: 2rem;
            padding: 2px 10px;
            font-weight: 600;
            background-color: var(--page-background);
            color: #262B4A;
        }

        .toast-danger .toast-header .toast-title {
            background-color: var(--flat-color-danger);
            color: white;
        }

        .toast-warning .toast-header .toast-title {
            background-color: var(--flat-color-warning);
            color: #262B4A;
        }

        .toast-success .toast-header .toast-title {
            background-color: var(--flat-color-primary);
            color: white;
        }

        .toast-close 
        {
            background: none;
            padding: 4px;
            width: 30px;
            height: 30px;
            border: none;
            outline: none !important;
            font-size: 16px;
        }

        .toast-close:hover i {
            opacity: 1;
        }

        .toast-close i {
            color: white;
            opacity: .25;
            font-weight: normal;
        }
    </style>
@endpush
@push('scripts')
    <script>
 
        $(function()
        {
            $('body').prepend(`<div class="toast-stack-frame flex-row gap-2"></div>`)
        });

        function showToast(message, title, options) 
        {  
            window.opts = options;

            title = title || 'Alert';

            var autoHide        = false;
            var toastType       = 'toast-success';
            var id              = 'toast' + generateRandomString(5, '_');

            if (options) 
            {
                if (options.hasOwnProperty('autohide'))
                    autoHide = options.autoHide; 

                if (options.hasOwnProperty('type') && !isNaN(parseInt(options.type)))
                {   
                    var _type = parseInt(options.type);

                    switch (_type)
                    {
                        case -1:
                            toastType = 'toast-danger'; 
                            title = 'Failure';
                            break;
                    
                        case 1:
                            toastType = 'toast-warning'; 
                            title = 'Warning';
                            break;

                        case 0:
                            toastType = 'toast-success'; 
                            title = 'Success';
                            break;
                    }
                }
            }
 
            var toastBase = 
            $(`<div class="toast ${toastType}" id="${id}" role="alert" aria-live="assertive"
                aria-atomic="true" data-mdb-autohide="${autoHide}" data-mdb-append-to-body="true" data-mdb-width="350px">
                <div class="toast-header">
                    <div class="me-auto toast-title">${title}</div>
                    <button type="button" class="toast-close" aria-label="Close">
					    <i class="fas fa-xmark"></i>
				    </button>
                </div>
                <div class="toast-body">${message}</div>
            </div>`);

            $('.toast-stack-frame').prepend(toastBase);
            
            var el = $(`#${id}`);
            
            el.on('hidden.bs.toast', () => {
                // console.warn('hide');
                toast = null;
                el.remove();
            });

            el.find('.toast-close').on('click', () => {
                closeToast(toastBase, toast);
            });

            var toast    = new mdb.Toast(el);
            toast.show();
            //window.toast = toast;

            setTimeout(() => { 
                toastBase.css({'opacity': '1', 'margin-top': '10px'});
            }, 100);

            setTimeout(() => {
                closeToast(toastBase, toast);
            }, 2900);
        }

        function closeToast(toastBase, toast)
        {
            if (toastBase)
                toastBase.css({'margin-top': '0', 'opacity': '0'});

            if (toast)
                toast.hide();
        }

    </script>
@endpush