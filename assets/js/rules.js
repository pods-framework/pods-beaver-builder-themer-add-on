
console.log( 'TEST');

var addRuleTypeCategory = BBLogic.api.addRuleTypeCategory,
    addRuleType = BBLogic.api.addRuleType,
    getFormPreset = BBLogic.api.getFormPreset,
    __ = BBLogic.i18n.__;


addRuleTypeCategory('pods', {
    label: __('Pods')
});


addRuleType('pods/settings-field', {
    label: __('Pods Settings Field'),
    category: 'pods',
    form: getFormPreset('key-value'),  // string,date,number,string
});


addRuleType('pods/date-field', {
    label: __('Pods Date Field'),
    category: 'pods',
    form: function ( props ) {
        var rule = props.rule;
        var post_type = rule.post_type;
        var taxonomy = rule.taxonomy;

        console.log( props );

        return {
            post_type: {
                type: 'select',
                route: 'bb-logic/v1/wordpress/post-types'
            },
            key: {
                type: 'text',
                placeholder: __('Key')
            },
            operator: {
                type: 'operator',
                operators: ['equals', 'does_not_equal']
            },
            taxonomy: {
                type: 'select',
                route: 'bb-logic/v1/wordpress/taxonomies'
            },
            term: {
                type: 'select',
                route: taxonomy ? 'bb-logic/v1/wordpress/terms?taxonomy=' + taxonomy : null,
                visible: taxonomy
            }
        };
    }  // string,date,number,string
});


// Test / Example
addRuleType( 'pods/user-post-count', {
    label: __( 'User Post Count' ),
    category: 'user',
    form: {
        operator: {
            type: 'operator',
            operators: [
                'equals',
                'does_not_equal',
                'is_less_than',
                'is_greater_than',
            ],
        },
        count: { //compare
            type: 'number',
            defaultValue: '0',
        },
        my_select: {
            type: 'select',
            // route: 'bb-logic/v1/wordpress/taxonomies',
            options: [
                {
                    label: 'Option 1',
                    value: '1',
                },
                {
                    label: 'Option 2',
                    value: '2',
                },
            ],
        },
    },
} )
