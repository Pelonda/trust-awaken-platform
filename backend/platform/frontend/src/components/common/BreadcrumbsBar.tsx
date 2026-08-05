import {
  Breadcrumbs,
  Link,
  Typography,
} from '@mui/material'

import NavigateNextIcon from '@mui/icons-material/NavigateNext'

import { Link as RouterLink, useLocation } from 'react-router-dom'

export default function BreadcrumbsBar() {

  const location = useLocation()

  const paths = location.pathname
    .split('/')
    .filter(Boolean)

  return (

    <Breadcrumbs
      separator={<NavigateNextIcon fontSize="small" />}
      sx={{ mb: 3 }}
    >

      <Link
        component={RouterLink}
        underline="hover"
        color="inherit"
        to="/dashboard"
      >
        Dashboard
      </Link>

      {paths
        .filter((item) => item !== 'dashboard')
        .map((item, index) => (

          <Typography
            key={index}
            color="text.primary"
            sx={{
              textTransform: 'capitalize',
            }}
          >
            {item.replace('-', ' ')}
          </Typography>

        ))}

    </Breadcrumbs>

  )

}