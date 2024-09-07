import {gql} from '@apollo/client';

export const REPORT_FRAGMENT_MINIMAL = gql`
    fragment ReportFragmentMinimal on Report {
        id
        title
        slug
    }
`;

export const REPORT_FRAGMENT = gql`
    fragment ReportFragment on Report {
        id
        title
        description
        from_date
        to_date
        charts {
            id
            chart_name
            chart_type
            chart_labels
            chart_values
        }
    }
`;
