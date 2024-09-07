import React from "react";
import {useQuery} from "@apollo/client";
import {REPORT_QUERY} from "@/Graphql/Queries/Report";
import ChartDetail from "@/Components/Chart";


interface ReportDetailsProps {
    slug: string
}

const ReportDetails: React.FC<ReportDetailsProps> = ({slug}) => {

    const {loading, error, data} = useQuery(REPORT_QUERY,{
        variables: {
            slug
        }
    });

    if (loading) {
        return (
            <div className="mt-4 bg-gray-700 w-full p-4 rounded-lg">
                <h2 className="text-white text-xl font-bold">Loading</h2>
            </div>
        );
    }


    return (
        <div className="mt-4 bg-gray-700 w-full p-4 rounded-lg">
            <h2 className="text-white text-xl font-bold">
                {data?.report?.title}
            </h2>
            <p className="text-white text-lg mt-4">
                {data?.report?.description}
            </p>

            {/*3 Col Grid on Desktop, 1 col on mobile*/}
            <div className={"grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4"}>
                {data?.report?.charts.map((chart: any) => (
                    <ChartDetail chart={chart} key={chart.id}/>
                ))}
            </div>
        </div>
    );
}

export default ReportDetails;
