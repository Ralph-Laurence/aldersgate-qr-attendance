//import moment from '../lib/momentjs/moment-with-locales.js';

class FlatTimePicker
{
    constructor(elementId)
    {
        this.$mainControl    = $(`${elementId}-modal`);
        this.$textOutput     = $(elementId);
        
        this.__initComponents();
        this.__bindEvents();
    }

    __initComponents()
    {
        this.$hourInput      = this.$mainControl.find('#input-hours');
        this.$minuteInput    = this.$mainControl.find('#input-minutes');
        this.$hourIncrement  = this.$mainControl.find('.btn-increment-hour');
        this.$hourDecrement  = this.$mainControl.find('.btn-decrement-hour');
        this.$minsIncrement  = this.$mainControl.find('.btn-increment-minute');
        this.$minsDecrement  = this.$mainControl.find('.btn-decrement-minute');
        this.$hourLabel      = this.$mainControl.find('.hour-label');
        this.$minsLabel      = this.$mainControl.find('.minute-label');
        this.$meridiemToggle = this.$mainControl.find('.meridiem-toggle');
        this.$meridiemLabel  = this.$mainControl.find('.meridiem-label');

        this.$clearButton    = this.$mainControl.find('.btn-clear');
        this.$okButton       = this.$mainControl.find('.btn-ok');
    }

    __bindEvents()
    {
        this.$mainControl.on('show.bs.modal', (e) => {

            var output = this.$textOutput.val();

            if (output)
            {
                var time = moment(output, 'hh:mm A');

                this.setHour(time.format('hh'));
                this.setMinute(time.format('mm'));
                this.setMeridiem(time.format('A'));

                return;
            }

            this.setFromCurrent();
        });

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

        this.$meridiemToggle.on('click', (e) => 
        {
            var $control      = $(e.currentTarget);
            var currentPeriod = $control.attr('data-time-period');

            switch ( $control.attr('data-meridiem') )
            {
                case 'am':
                    $control.attr('data-meridiem', 'pm');
                    $control.find('.meridiem-label').text('pm');

                    $control.attr('data-time-period', this.getCurrentTimePeriod());
                    break;

                case 'pm':
                    $control.attr('data-meridiem', 'am');
                    $control.find('.meridiem-label').text('am');

                    if (currentPeriod != 'morning')
                        $control.attr('data-time-period', 'morning');

                    break;
            }
        });

        this.$clearButton.on('click', () => this.clear());
        this.$okButton.on('click', () => this.applyOutput());
    }
    
    setMeridiem(meridiem)
    {
        switch ( meridiem )
        {
            case 'am':
                this.$meridiemToggle.attr('data-meridiem', 'am').attr('data-time-period', 'morning');
                this.$meridiemLabel.text('am');
                break;

            case 'pm':
                this.$meridiemToggle.attr('data-meridiem', 'pm').attr('data-time-period', this.getCurrentTimePeriod());
                this.$meridiemLabel.text('pm');
                break;
        }
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
        this.setMeridiem(temp.format('a'));
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

    /**
     * Gets the current time period if it is Morning, Noon or Night
     * @returns string time period
     */
    getCurrentTimePeriod()
    {
        //var date = moment(time, 'hh:mm:ss a');
        var hour = moment().format('H');
    
        if (hour >= 5 && hour < 12)
            return "morning";

        else if (hour >= 12 && hour < 17)
            return "noon";

        else
            return "night";
    }

    getMeridiem()
    {
        return this.$meridiemToggle.attr('data-meridiem');
    }

    padLeft(num)
    {
        return String(num).padStart(2, '0');
    }

    clear()
    {
        this.$textOutput.val('');
    }

    applyOutput()
    {
        var hours = this.getHour();
        var mins  = this.getMinute();
        var ampm  = this.getMeridiem();

        this.$textOutput.val(`${hours}:${mins} ${ampm}`);
    }
}