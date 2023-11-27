export class FlatDatePicker
{
    constructor(elementId)
    {
        $((e) => this.__initComponents(elementId));
    }

    __initComponents(elementId)
    {
        this.$textInput = $(elementId);

        if (this.$textInput.length < 1)
            throw new Error(`Can't initialize the date picker because "${elementId}" does not exist.`);

        this.$root = this.$textInput.closest('.flat-controls.flat-input.flat-date-picker');
        
        // Parent element where of the date picker dropdown menu
        this.dropmenuRoot   = this.$root.find('.date-picker-dropmenu');
        this.dayNamesHeader = this.dropmenuRoot.find('.day-names-header');
        this.dayValueCells  = this.dropmenuRoot.find('.day-values');

        var toggle = this.$root.find('.date-picker-toggle');

        this.dropdownInstance = new mdb.Dropdown(toggle, {
            autoClose: false
        });

        this.yearDropMenu    = this.$root.find('.year-dropdown');
        this.yearDropToggle  = this.yearDropMenu.find('.dropdown-toggle');
        this.yearOptions     = this.yearDropMenu.find('.dropdown-menu .menu-scrollview');

        this.monthDropMenu   = this.$root.find('.month-dropdown');
        this.monthDropToggle = this.monthDropMenu.find('.dropdown-toggle');
        this.monthOptions    = this.monthDropMenu.find('.dropdown-menu .menu-scrollview');

        this.btnMonthPrev    = this.dropmenuRoot.find('.month-spinner-prev');
        this.btnMonthNext    = this.dropmenuRoot.find('.month-spinner-next');

        this.dayLabel        = this.dropmenuRoot.find('.day-label');

        this.controlBtnOK       = this.dropmenuRoot.find('.date-picker-footer .btn-ok');
        this.controlBtnCancel   = this.dropmenuRoot.find('.date-picker-footer .btn-cancel');
        this.controlBtnClear    = this.dropmenuRoot.find('.date-picker-footer .btn-clear');

        var currentMonth   = moment().month();
        var currentYear    = moment().year();

        this.selectedMonth = currentMonth;
        this.selectedYear  = currentYear;
        this.selectedDay   = moment().date();
        
        this.monthCycleOffset = currentMonth;

        this.render(currentMonth, currentYear);

        this.__bindEvents();
    }

    __bindEvents()
    {
        this.controlBtnOK.click(() =>
        {
            console.warn([this.selectedYear, this.selectedMonth, this.selectedDay]);

            var output = moment([this.selectedYear, this.selectedMonth, this.selectedDay]);
            
            this.$textInput.val(output.format('MMM. D, YYYY'));
            this.close();
            // if (output.isValid())
            // {
            //     this.$textInput.val(output.format('MMM. D, YYYY'));
            // }
        });

        this.controlBtnClear.on('click',  () => 
        {
            this.clear();
            this.close();
        });

        this.controlBtnCancel.on('click', () => this.close());
        this.$textInput.on('focus',       () => this.show());

        this.btnMonthPrev.on('click',     () => this.cyclePrevMonth());
        this.btnMonthNext.on('click',     () => this.cycleNextMonth());

        $(document).on('click', '.flat-date-picker .date-picker-dropmenu .dropdown-item.year-option', (e) => 
        {
            this.selectedDay = null;
            this.toggleOkButton(false);

            var year = $(e.target).data('value');
            this.render(this.selectedMonth, year);
        })
        .on('click', '.flat-date-picker .date-picker-dropmenu .dropdown-item.month-option', (e) => 
        {
            this.selectedDay = null;
            this.toggleOkButton(false);

            var month = $(e.target).data('value');
            this.render(month, this.selectedYear);
        });
    }

    populateYear(year)
    {
        this.yearOptions.empty();

        var currentYear = moment().year();

        // for (var i = year - 10; i <= year + 10; i++)
        for (var i = 2020; i <= currentYear + 5; i++)
        {
            var a = $('<a></a>')
                        .addClass('dropdown-item year-option')
                        .attr('role', 'button')
                        .attr('data-value', i)
                        .text(i);
            
            if (i === year)
            {
                a.addClass('active');
                this.yearDropToggle.text(i);
                this.selectedYear = i;
            }
            
            var li = $('<li></li>').append(a);
            
            this.yearOptions.append(li);
        }
    }

    populateMonth(month)
    {
        this.monthOptions.empty();
        this.dayLabel.text('Select a date');

        for (var i = 0; i < 12; i++)
        {
            var monthString = moment().month(i).format('MMMM');

            var a = $('<a></a>')
                .addClass('dropdown-item month-option')
                .attr('role', 'button')
                .attr('data-value', i)
                .text(monthString);

            if (i === month)
            {
                a.addClass('active');
                this.monthDropToggle.text(monthString);
                this.selectedMonth = i;
            }

            var li = $('<li></li>').append(a);

            this.monthOptions.append(li);
        }
    }

    populateDayNames()
    {
        this.dayNamesHeader.empty();

        for (var i = 0; i < 7; i++)
        {
            this.dayNamesHeader.append($('<th>', {
                text: moment().day(i).format('ddd')
            }));
        }
    }

    // populateDays(month, year, newday)
    // {
    //     var startDay        = moment([year, month]);
    //     var daysInMonth     = startDay.daysInMonth();
    //     var weekdayOfFirst  = startDay.day();
    //     var today           = moment();

    //     this.dayValueCells.empty();

    //     var row = $('<tr>');

    //     for (var i = 0; i < 42; i++)
    //     {
    //         if (i < weekdayOfFirst || i >= daysInMonth + weekdayOfFirst)
    //         {
    //             row.append($('<td>'));
    //         }
    //         else
    //         {
    //             var day = i - weekdayOfFirst + 1;
    //             var isSelected = '';

    //             if (newday)
    //             {
    //                 if (day === newday)
    //                 {
    //                     isSelected = 'selected';
    //                     this.toggleOkButton(true);

    //                     var selectedDate = moment([year, month, day]);
    //                     this.dayLabel.text(selectedDate.format('dddd, MMM. D'));
    //                 }
    //             }
    //             else 
    //             {
    //                 if (day === today.date() && month === today.month() && year === today.year())
    //                 {
    //                     this.selectedDay = day;
    //                     isSelected = 'selected';
    //                     this.toggleOkButton(true);

    //                     var selectedDate = moment([year, month, day]);
    //                     this.dayLabel.text(selectedDate.format('dddd, MMM. D'));
    //                 }
    //             }

    //             var span = $('<span>', {
    //                 text: day,
    //                 class: isSelected
    //             })
    //             .click((e) =>
    //             {
    //                 $('.days-table td span.selected').removeClass('selected');
    //                 $(e.currentTarget).addClass('selected');

    //                 var selectedDay = parseInt($(e.target).text());

    //                 if (!isNaN(selectedDay))
    //                 {
    //                     this.selectedDay = selectedDay;
    //                     this.toggleOkButton(true);
    //                 }
    //                 else
    //                 {
    //                     this.toggleOkButton(false);
    //                 }
    //             });

    //             var td = $('<td>').append(span);
    //             row.append(td);
    //         }
    //         if (i % 7 === 6)
    //         {
    //             this.dayValueCells.append(row);
    //             row = $('<tr>');
    //         }
    //     }
    // }

    populateDays(month, year, newday)
    {
        var startDay        = moment([year, month]);
        var daysInMonth     = startDay.daysInMonth();
        var weekdayOfFirst  = startDay.day();
        var today           = moment();

        this.dayValueCells.empty();

        var row = $('<tr>');

        for (var i = 0; i < 42; i++)
        {
            if (i < weekdayOfFirst || i >= daysInMonth + weekdayOfFirst)
                row.append($('<td>'));

            else
            {
                var day = i - weekdayOfFirst + 1;
                var isSelected = '';

                var selectedDate = moment([year, month, day]);
                var isToday      = day === today.date() && month === today.month() && year === today.year();

                if ((newday && day === newday) || (!newday && isToday))
                {
                    isSelected = 'selected';
                    this.toggleOkButton(true);
                    //this.dayLabel.text(selectedDate.format('dddd, MMM. D'));
                    this.renderDayLabel(selectedDate);

                    if (isToday)
                        this.selectedDay = day;
                }

                var span = $('<span>', {
                    text: day,
                    class: isSelected
                })
                .click((e) =>
                {
                    $('.days-table td span.selected').removeClass('selected');
                    $(e.currentTarget).addClass('selected');

                    var selectedDay = parseInt($(e.target).text());

                    if (!isNaN(selectedDay))
                    {
                        this.selectedDay = selectedDay;

                        this.renderDayLabel(moment([year, month, selectedDay]));

                        this.toggleOkButton(true);
                    }
                    else
                        this.toggleOkButton(false);
                });

                var td = $('<td>').append(span);
                row.append(td);
            }
            if (i % 7 === 6)
            {
                this.dayValueCells.append(row);
                row = $('<tr>');
            }
        }
    }

    cyclePrevMonth() 
    {
        this.toggleOkButton(false);

        this.monthCycleOffset --;
        
        if (this.monthCycleOffset < 0)
            this.monthCycleOffset = 11;
            
        this.render(this.monthCycleOffset, this.selectedYear);
    }

    cycleNextMonth() 
    {
        this.toggleOkButton(false);

        this.monthCycleOffset ++;
        
        if (this.monthCycleOffset > 11)
            this.monthCycleOffset = 0;

        this.render(this.monthCycleOffset, this.selectedYear);
    }

    /**
     * Enables / Disables the OK button
     * @param {boolean} toggle 
     */
    toggleOkButton(toggle) {
        this.controlBtnOK.prop('disabled', !toggle);
    }

    render(month, year, day)
    {
        this.populateYear(year);
        this.populateMonth(month);
        this.populateDayNames();
        this.populateDays(month, year, day);
    }

    renderDayLabel(momentDate)
    {
        this.dayLabel.text(momentDate.format('dddd, MMM. D'));
    }

    clear()
    {
        var current = moment();

        this.$textInput.val('');
        this.selectedDay    = current.date();
        this.selectedMonth  = current.month();
        this.selectedYear   = current.year();
        
        this.monthCycleOffset = current.month();
    }

    close() {
        this.dropdownInstance.hide();
    }

    show()
    {
        // If we have an exising date from the input,
        // we check if it is a valid date then apply 
        // that as initial value
        if ( this.$textInput.val() && moment(this.$textInput.val(), "MMM. D, YYYY").isValid() )
        {
            var d = moment(this.$textInput.val(), "MMM. D, YYYY");
            this.render(d.month(), d.year(), d.date());
        }

        // We create a new date as a fallback
        else
        {
            this.render(moment().month(), moment().year());
        }

        this.dropdownInstance.show();
    }
}