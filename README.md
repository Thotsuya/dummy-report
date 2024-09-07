
## Simple Dummy Charting

This is a simple dummy charting application that uses Laravel , React, GraphQL to display a Dashboard with dummy data. Some features include:

- Use of Laravel as a backend to serve the data with GraphQL ( Lighthouse )
- Use of React to display the data in a dashboard
- Use of Apex Charts to display the data in a chart
- Use of Tailwind CSS for styling, Mobile Responsive design
- Apollo Client to fetch data from the GraphQL server
- The Project is Structured in a way that it can be easily extended to add more features

## Installation

1. Clone the repository
2. Run `composer install`
3. Run `npm install`
4. Run `npm run dev`
5. Run `php artisan serve`
6. Visit `http://localhost:8000` in your browser
7. You can also visit `http://localhost:8000/graphiql` to test the GraphQL queries

## Notes

- The project uses SQLite as the database, you can change the database configuration in the `.env` file
- ApexCharts is not very good at displaying charts in responsive mode, so the charts may not look good in smaller screens using this approach
