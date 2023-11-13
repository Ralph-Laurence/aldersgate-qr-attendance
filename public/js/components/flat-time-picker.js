//import moment from '../lib/momentjs/moment-with-locales.js';

class FlatTimePicker
{
    constructor(elementId)
    {
        this.$mainControl   = $(`${elementId}-modal`);
        
        this.$hourInput     = this.$mainControl.find('#input-hours');
        this.$minuteInput   = this.$mainControl.find('#input-minutes');
        this.$hourIncrement = this.$mainControl.find('.btn-increment-hour');
        this.$hourDecrement = this.$mainControl.find('.btn-decrement-hour');
        this.$minsIncrement = this.$mainControl.find('.btn-increment-minute');
        this.$minsDecrement = this.$mainControl.find('.btn-decrement-minute');
        this.$hourLabel     = this.$mainControl.find('.hour-label');
        this.$minsLabel     = this.$mainControl.find('.minute-label');
        this.meridiemToggle = this.$mainControl.find('.meridiem-toggle');

        this.__bindEvents();
    }

    __bindEvents()
    {
        this.$mainControl.on('show.bs.modal', (e) => this.setFromCurrent());

        this.$mainControl.find('.btn-reset').on('click', (e) => this.setFromCurrent());

        this.$hourInput.on('input', (e) => 
        {
            var currentValue = parseInt($(e.currentTarget).val());

            if (isNaN(currentValue) || currentValue < 1 || currentValue > 12)
            {
                this.setHour( 1 );
                currentValue = 1;
            }
            else
            {
                $(e.currentTarget).val(this.padLeft(currentValue));
            }
        });

        this.$minuteInput.on('input', (m) => 
        {
            var currentValue = parseInt( $(m.currentTarget).val() );

            if (isNaN(currentValue) || currentValue > 59 || currentValue < 0)
            {
                this.setMinute( 0 );
                currentValue = 0;
            }
            else
            {
                $(m.currentTarget).val(this.padLeft(currentValue));
            }
        });

        this.$hourIncrement.on('click', (e) => 
        {
            var hour = this.getHour(false);

            if (hour < 12)
                hour++;
            else
                hour = 1;

            this.setHour(hour);
        });

        this.$minsIncrement.on('click', (e) => 
        {
            var minute = this.getMinute(false);

            if (minute < 59)
                minute++;
            else
                minute = 0;

            this.setMinute(minute);
        });

        this.$hourDecrement.on('click', (e) => 
        {
            var hour = this.getHour(false);

            if (hour > 1)
                hour--;
            else
                hour = 12;

            this.setHour(hour);
        });

        this.$minsDecrement.on('click', (e) => 
        {
            var minute = this.getMinute(false);

            if (minute > 0)
                minute--;
            else
                minute = 59;

            this.setMinute(minute);
        });

        this.meridiemToggle.on('click', (e) => 
        {
            var $control = $(e.currentTarget);
            var data = $control.attr('data-meridiem');
            
            switch (data)
            {
                case 'am':
                    $control.text('pm').attr('data-meridiem', 'pm');
                    break;

                case 'pm':
                    $control.text('am').attr('data-meridiem', 'am');
                    break;
            }
        });
    }

    setTime(timeString)
    {
        var temp = moment(timeString, 'hh:mm A');

        this.setHour(temp.format('hh'));
        this.setMinute(temp.format('mm'));
    }

    setHour(hour) 
    {
        if (parseInt(hour) < 10)
            hour = this.padLeft(hour);

        this.$hourInput.val( hour ).trigger('change');
    }

    setMinute(minute) 
    {
        this.$minuteInput.val( this.padLeft(minute) ).trigger('change');
    }

    setFromCurrent()
    {
        var temp = moment();

        this.setHour(temp.format('hh'));
        this.setMinute(temp.format('mm'));
    }

    getCurrentTime()
    {
        return moment().format('hh:mm A');
    }

    getHour(pad = true) 
    {
        var val = this.$hourInput.val();

        if (isNaN(val))
            val = this.padLeft(1);

        return (pad != undefined && typeof pad === 'boolean' && pad === true) ? val : parseInt(val);
    }

    getMinute(pad = true) 
    {
        var val = this.$minuteInput.val();

        if (isNaN(val))
            val = this.padLeft(0);

        return (pad != undefined && typeof pad === 'boolean' && pad === true) ? val : parseInt(val);
    }

    padLeft(num)
    {
        return String(num).padStart(2, '0');
    }
}