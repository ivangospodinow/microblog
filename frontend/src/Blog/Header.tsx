import * as React from 'react';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import SearchIcon from '@mui/icons-material/Search';
import Typography from '@mui/material/Typography';
import Link from '@mui/material/Link';
import { Stack } from '@mui/material';
import GitHubIcon from '@mui/icons-material/GitHub';
import HomeIcon from '@mui/icons-material/Home';
import AuthButtonsComponent from './Component/AuthButtonsComponent';
import { BlogProps } from './Blog';

export default function Header(props: BlogProps) {

  return (
    <React.Fragment>
      <Toolbar sx={{ borderBottom: 1, borderColor: 'divider' }}>
        <Link
          display="block"
          variant="body1"
          href={'https://github.com/ivangospodinow/'}
          sx={{ mb: 0.5 }}
        >
          <Stack direction="row" spacing={1} alignItems="center">
            <GitHubIcon />
            <span>Fork me on github</span>
          </Stack>
        </Link>

        <Typography
          component="h2"
          variant="h5"
          color="inherit"
          align="center"
          noWrap
          sx={{ flex: 1 }}
          onClick={() => {
            window.location.href = '/';
          }}
          style={{
            cursor: 'pointer',
          }}>
          Microblog
        </Typography>

        <IconButton>
          <SearchIcon />
        </IconButton>


        <IconButton onClick={() => {
          window.location.href = '/';
        }}>
          <HomeIcon fontSize="inherit" />
        </IconButton>
        &nbsp;
        <AuthButtonsComponent {...props} />

      </Toolbar>
    </React.Fragment>
  );
}
