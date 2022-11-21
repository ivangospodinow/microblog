import { useEffect, useState } from "react";
import Grid from '@mui/material/Grid';
import Stack from '@mui/material/Stack';
import Paper from '@mui/material/Paper';
import Typography from '@mui/material/Typography';
import Link from '@mui/material/Link';
import GitHubIcon from '@mui/icons-material/GitHub';
import { BlogProps } from "./Blog";
import { BlogMonths } from "../Service/DataService";
import BlogMonthsComponent from "./Component/BlogMonthsComponent";

const sidebar = {
  title: 'About',
  description:
    'Hello there, this is a Microblog project designed and created for an interview ask. Backend is valina php with Slim3 for routing. Frontend is ReactJs with Material UI',
  archives: [
    { title: 'March 2020', url: '#' },
    { title: 'February 2020', url: '#' },
    { title: 'January 2020', url: '#' },
    { title: 'November 1999', url: '#' },
    { title: 'October 1999', url: '#' },
    { title: 'September 1999', url: '#' },
    { title: 'August 1999', url: '#' },
    { title: 'July 1999', url: '#' },
    { title: 'June 1999', url: '#' },
    { title: 'May 1999', url: '#' },
    { title: 'April 1999', url: '#' },
  ],
  social: [
    { name: 'GitHub', icon: GitHubIcon, url: 'https://github.com/ivangospodinow/' },
    // { name: 'Twitter', icon: TwitterIcon },
    // { name: 'Facebook', icon: FacebookIcon },
  ],
};



export default function Sidebar(props: BlogProps) {
  const { description, social, title } = sidebar;

  const [months, setMonths] = useState<BlogMonths>([]);
  const [monthsLoaded, setMonthsLoaded] = useState(false);

  useEffect(() => {

    (async () => {
      const months = await props.dataService.getMonths();
      setMonthsLoaded(true);
      setMonths(months);
    })();


  }, [props, monthsLoaded]);

  return (
    <Grid item xs={12} md={4}>
      <Paper elevation={0} sx={{ p: 2, bgcolor: 'grey.200' }}>
        <Typography variant="h6" gutterBottom>
          {title}
        </Typography>
        <Typography>{description}</Typography>
      </Paper>
      <Typography variant="h6" gutterBottom sx={{ mt: 3 }}>
        Archives
      </Typography>

      <BlogMonthsComponent months={months} monthsLoaded={monthsLoaded} />

      <Typography variant="h6" gutterBottom sx={{ mt: 3 }}>
        Social
      </Typography>
      {social.map((network) => (
        <Link
          display="block"
          variant="body1"
          href={network.url}
          key={network.name}
          sx={{ mb: 0.5 }}
        >
          <Stack direction="row" spacing={1} alignItems="center">
            <network.icon />
            <span>{network.name}</span>
          </Stack>
        </Link>
      ))}
    </Grid>
  );
}
