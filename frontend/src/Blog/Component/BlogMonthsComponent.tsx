import { BlogMonth, BlogMonths } from '../../Service/DataService';
import { Skeleton } from '@mui/material';
import { SIDEBAR_DEFAULT_MONTHS_BACK_COUNT } from '../../config';
import Link from '@mui/material/Link';
import moment from 'moment';
import Alert from '@mui/material/Alert';

interface MainProps {
    months: BlogMonths;
    monthsLoaded: boolean,
}

export default function BlogMonthsComponent(props: MainProps) {
    const { months, monthsLoaded } = props;

    return (
        <>
            {!monthsLoaded && (
                <div>
                    {Array(SIDEBAR_DEFAULT_MONTHS_BACK_COUNT).fill(0).map(() => {
                        return (
                            <Skeleton variant="rectangular" width={'100%'} height={20} style={{
                                marginBottom: '2rem',
                            }} />
                        );
                    })}
                </div>
            )}

            {monthsLoaded && undefined === months && (
                <Alert severity="error">Unable to load blog archive.</Alert>
            )}

            {monthsLoaded && months && months.map((pair: BlogMonth) => (
                <Link key={pair.month} display="block" variant="body1" href={'/posts/1?archive=' + pair.month}>
                    {moment(pair.month).format('MMMM YYYY') + ' (' + pair.count + ')'}
                </Link>
            ))}
        </>
    );
}
