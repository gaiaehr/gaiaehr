/**
 * Hebrew Translations
 * By spartacus (from forums) 06-12-2007
 */
Ext.onReady(function() {
    var cm = Ext.ClassManager,
        exists = Ext.Function.bind(cm.get, cm);

    if (Ext.Updater) {
        Ext.Updater.defaults.indicatorText = '<div class="loading-indicator">...����</div>';
    }

    Ext.define("Ext.locale.he.view.View", {
        override: "Ext.view.View",
        emptyText: ""
    });

    Ext.define("Ext.locale.he.grid.Panel", {
        override: "Ext.grid.Panel",
        ddText: "����� ������ {0}"
    });

    Ext.define("Ext.locale.he.TabPanelItem", {
        override: "Ext.TabPanelItem",
        closeText: "���� ������"
    });

    Ext.define("Ext.locale.he.form.field.Base", {
        override: "Ext.form.field.Base",
        invalidText: "���� ���� �� ����"
    });

    // changing the msg text below will affect the LoadMask
    Ext.define("Ext.locale.he.view.AbstractView", {
        override: "Ext.view.AbstractView",
        msg: "...����"
    });

    if (Ext.Date) {
        Ext.Date.monthNames = ["�����", "������", "���", "�����", "���", "����", "����", "������", "������", "�������", "������", "�����"];

        Ext.Date.getShortMonthName = function(month) {
            return Ext.Date.monthNames[month].substring(0, 3);
        };

        Ext.Date.monthNumbers = {
            Jan: 0,
            Feb: 1,
            Mar: 2,
            Apr: 3,
            May: 4,
            Jun: 5,
            Jul: 6,
            Aug: 7,
            Sep: 8,
            Oct: 9,
            Nov: 10,
            Dec: 11
        };

        Ext.Date.getMonthNumber = function(name) {
            return Ext.Date.monthNumbers[name.substring(0, 1).toUpperCase() + name.substring(1, 3).toLowerCase()];
        };

        Ext.Date.dayNames = ["�", "�", "�", "�", "�", "�", "�"];

        Ext.Date.getShortDayName = function(day) {
            return Ext.Date.dayNames[day].substring(0, 3);
        };
    }

    if (Ext.MessageBox) {
        Ext.MessageBox.buttonText = {
            ok: "�����",
            cancel: "�����",
            yes: "��",
            no: "��"
        };
    }

    if (exists('Ext.util.Format')) {
        Ext.apply(Ext.util.Format, {
            thousandSeparator: '.',
            decimalSeparator: ',',
            currencySign: '\u20aa',
            // Iraeli Shekel
            dateFormat: 'd/m/Y'
        });
    }

    Ext.define("Ext.locale.he.picker.Date", {
        override: "Ext.picker.Date",
        todayText: "����",
        minText: ".����� �� �� ���� ������ ������� �����",
        maxText: ".����� �� �� ���� ������ ����� �����",
        disabledDaysText: "",
        disabledDatesText: "",
        monthNames: Ext.Date.monthNames,
        dayNames: Ext.Date.dayNames,
        nextText: '(Control+Right) ����� ���',
        prevText: '(Control+Left) ����� �����',
        monthYearText: '(������ ��� Control+Up/Down) ��� ����',
        todayTip: "��� ����) {0})",
        format: "d/m/Y",
        startDay: 0
    });

    Ext.define("Ext.locale.he.picker.Month", {
        override: "Ext.picker.Month",
        okText: "&#160;�����&#160;",
        cancelText: "�����"
    });

    Ext.define("Ext.locale.he.toolbar.Paging", {
        override: "Ext.PagingToolbar",
        beforePageText: "����",
        afterPageText: "{0} ����",
        firstText: "���� �����",
        prevText: "���� ����",
        nextText: "���� ���",
        lastText: "���� �����",
        refreshText: "����",
        displayMsg: "���� {0} - {1} ���� {2}",
        emptyMsg: '��� ���� �����'
    });

    Ext.define("Ext.locale.he.form.field.Text", {
        override: "Ext.form.field.Text",
        minLengthText: "{0} ����� ��������� ���� �� ���",
        maxLengthText: "{0} ����� ������ ���� �� ���",
        blankText: "��� �� �����",
        regexText: "",
        emptyText: null
    });

    Ext.define("Ext.locale.he.form.field.Number", {
        override: "Ext.form.field.Number",
        minText: "{0} ���� ��������� ���� �� ���",
        maxText: "{0} ���� ������ ���� �� ���",
        nanText: "��� �� ���� {0}"
    });

    Ext.define("Ext.locale.he.form.field.Date", {
        override: "Ext.form.field.Date",
        disabledDaysText: "������",
        disabledDatesText: "������",
        minText: "{0} ������ ���� �� ���� ����� ����",
        maxText: "{0} ������ ���� �� ���� ����� ����",
        invalidText: "{1} ��� �� ����� ���� - ���� ����� ������ {0}",
        format: "m/d/y",
        altFormats: "m/d/Y|m-d-y|m-d-Y|m/d|m-d|md|mdy|mdY|d|Y-m-d"
    });

    Ext.define("Ext.locale.he.form.field.ComboBox", {
        override: "Ext.form.field.ComboBox",
        valueNotFoundText: undefined
    }, function() {
        Ext.apply(Ext.form.field.ComboBox.prototype.defaultListConfig, {
            loadingText: "...����"
        });
    });

    if (exists('Ext.form.field.VTypes')) {
        Ext.apply(Ext.form.field.VTypes, {
            emailText: '"user@example.com" ��� �� ���� ����� ����� ���� �������� ������',
            urlText: '"http:/' + '/www.example.com" ��� �� ���� ����� ����� ������� ������',
            alphaText: '_��� �� ���� ����� �� ������ �',
            alphanumText: '_��� �� ���� ����� �� ������, ������ �'
        });
    }

    Ext.define("Ext.locale.he.form.field.HtmlEditor", {
        override: "Ext.form.field.HtmlEditor",
        createLinkText: ':��� ���� �� ����� �������� ���� ������'
    }, function() {
        Ext.apply(Ext.form.field.HtmlEditor.prototype, {
            buttonTips: {
                bold: {
                    title: '(Ctrl+B) �����',
                    text: '.���� �� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                italic: {
                    title: '(Ctrl+I) ����',
                    text: '.��� �� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                underline: {
                    title: '(Ctrl+U) �� ����',
                    text: '.���� �� ���� ���� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                increasefontsize: {
                    title: '���� ����',
                    text: '.���� ���� ���� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                decreasefontsize: {
                    title: '���� ����',
                    text: '.���� ���� ���� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                backcolor: {
                    title: '��� ��� �����',
                    text: '.��� �� ��� ���� ���� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                forecolor: {
                    title: '��� ����',
                    text: '.��� �� ��� ����� ���� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                justifyleft: {
                    title: '���� �����',
                    text: '.��� ����� �� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                justifycenter: {
                    title: '���� �����',
                    text: '.��� ����� �� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                justifyright: {
                    title: '���� �����',
                    text: '.��� ����� �� ����� �����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                insertunorderedlist: {
                    title: '����� ������',
                    text: '.���� ����� ������',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                insertorderedlist: {
                    title: '����� �������',
                    text: '.���� ����� �������',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                createlink: {
                    title: '�����',
                    text: '.���� �� ����� ����� ������',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                },
                sourceedit: {
                    title: '����� ��� ����',
                    text: '.��� ��� ����',
                    cls: Ext.baseCSSPrefix + 'html-editor-tip'
                }
            }
        });
    });

    Ext.define("Ext.locale.he.grid.header.Container", {
        override: "Ext.grid.header.Container",
        sortAscText: "���� ���� ����",
        sortDescText: "���� ���� ����",
        lockText: "��� �����",
        unlockText: "���� �����",
        columnsText: "������"
    });

    Ext.define("Ext.locale.he.grid.GroupingFeature", {
        override: "Ext.grid.GroupingFeature",
        emptyGroupText: '(���)',
        groupByText: '��� ������� ��� ��� ��',
        showGroupsText: '��� �������'
    });

    Ext.define("Ext.locale.he.grid.PropertyColumnModel", {
        override: "Ext.grid.PropertyColumnModel",
        nameText: "��",
        valueText: "���",
        dateFormat: "m/j/Y"
    });

});
