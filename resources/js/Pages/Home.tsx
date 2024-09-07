import React, {useState} from "react"
import {useQuery} from "@apollo/client";
import {REPORT_QUERY_MINIMAL} from "@/Graphql/Queries/Report";
import Select from "react-select";
import ReportDetails from "@/Components/ReportDetails";

export default function Home() {

    const [selectedReport, setSelectedReport] = useState<string | null>(null);
    const {loading, error, data} = useQuery(REPORT_QUERY_MINIMAL);
    if (loading) return <p>Loading...</p>;


    return (
        <>
            <div className="w-full bg-gray-700 h-full p-6 rounded-lg">
                <h1 className="text-white text-2xl font-bold">Dummy Showcase Reports</h1>

                <div className="mt-4">
                    <Select
                        options={data.reports.map((report: any) => ({value: report.slug, label: report.title}))}
                        onChange={(selectedOption: any) => {
                            setSelectedReport(selectedOption.value)
                        }}
                    />
                </div>
            </div>
            {selectedReport && (
                <ReportDetails slug={selectedReport}/>
            )}
        </>
    )
}

