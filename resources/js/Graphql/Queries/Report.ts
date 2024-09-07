import {gql} from '@apollo/client';
import {REPORT_FRAGMENT,REPORT_FRAGMENT_MINIMAL} from "@/Graphql/Fragments/Report";

export const REPORT_QUERY_MINIMAL = gql`
    query GetReports {
        reports {
            ...ReportFragmentMinimal
        }
    }
    ${REPORT_FRAGMENT_MINIMAL}
`;

export const REPORT_QUERY = gql`
    query GetReports($slug: String!) {
        report(slug: $slug) {
            ...ReportFragment
        }
    }
    ${REPORT_FRAGMENT}
`;
