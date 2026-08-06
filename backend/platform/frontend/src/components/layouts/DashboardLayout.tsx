import {
  AppBar,
  Avatar,
  Badge,
  Box,
  CssBaseline,
  Divider,
  Drawer,
  IconButton,
  List,
  ListItemButton,
  ListItemIcon,
  ListItemText,
  Toolbar,
  Typography,
} from '@mui/material'

import DashboardIcon from '@mui/icons-material/Dashboard'
import SchoolIcon from '@mui/icons-material/School'
import PeopleIcon from '@mui/icons-material/People'
import EventIcon from '@mui/icons-material/Event'
import FactCheckIcon from '@mui/icons-material/FactCheck'
import WorkspacePremiumIcon from '@mui/icons-material/WorkspacePremium'
import BusinessIcon from '@mui/icons-material/Business'
import AdminPanelSettingsIcon from '@mui/icons-material/AdminPanelSettings'
import NotificationsNoneIcon from '@mui/icons-material/NotificationsNone'
import DarkModeOutlinedIcon from '@mui/icons-material/DarkModeOutlined'
import OrganizationSwitcher from '../common/OrganizationSwitcher'
import DescriptionIcon from '@mui/icons-material/Description'

import { useState } from 'react'
import { Outlet, useLocation, useNavigate } from 'react-router-dom'

import { useAuth } from '../../context/AuthContext'

import BreadcrumbsBar from '../common/BreadcrumbsBar'
import GlobalSearch from '../common/GlobalSearch'
import NotificationDrawer from '../common/NotificationDrawer'
import ProfileMenu from '../common/ProfileMenu'

const drawerWidth = 270

interface MenuItem {
  title: string
  icon: React.ReactNode
  url: string
  permission: string
}

interface MenuGroup {
  group: string
  items: MenuItem[]
}

export default function DashboardLayout() {
  const navigate = useNavigate()

  const location = useLocation()

  const { hasPermission } = useAuth()

  const [search, setSearch] = useState('')

  const [notificationsOpen, setNotificationsOpen] =
    useState(false)

  const menu: MenuGroup[] = [
    {
      group: 'Overview',
      items: [
        {
          title: 'Dashboard',
          icon: <DashboardIcon />,
          url: '/dashboard',
          permission: 'dashboard.view',
        },
      ],
    },

    {
      group: 'Training',
      items: [
        {
          title: 'Programs',
          icon: <SchoolIcon />,
          url: '/programs',
          permission: 'program.view',
        },
        {
          title: 'Participants',
          icon: <PeopleIcon />,
          url: '/participants',
          permission: 'participant.view',
        },
        {
          title: 'Sessions',
          icon: <EventIcon />,
          url: '/sessions',
          permission: 'session.view',
        },
      ],
    },

    {
  group: 'Operations',

  items: [

    {
      title: 'Attendance',
      icon: <FactCheckIcon />,
      url: '/attendance',
      permission: 'attendance.view',
    },

    {
      title: 'Credentials',
      icon: <WorkspacePremiumIcon />,
      url: '/credentials',
      permission: 'credential.view',
    },

    {
      title: 'Document Studio',
      icon: <DescriptionIcon />,
      url: '/document-studio',
      permission: 'credential.view',
    },

  ],

},

    {
      group: 'Administration',
      items: [
        {
          title: 'Organizations',
          icon: <BusinessIcon />,
          url: '/organizations',
          permission: 'organization.view',
        },
        {
          title: 'Users',
          icon: <AdminPanelSettingsIcon />,
          url: '/users',
          permission: 'user.view',
        },
      ],
    },
  ]

  return (
    <Box sx={{ display: 'flex' }}>
      <CssBaseline />

      <AppBar
        elevation={0}
        color="inherit"
        position="fixed"
        sx={{
          borderBottom: '1px solid #e5e7eb',
          zIndex: 1300,
        }}
      >
        <Toolbar>

          <Box
            sx={{
              flexGrow: 1,
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              mr: 3,
            }}
          >

            <Box>

              <Typography
                variant="h6"
                fontWeight={700}
              >
                Trust AWAKEN
              </Typography>

              <Typography
                variant="caption"
                color="text.secondary"
              >
                Global CyberSafe
              </Typography>

            </Box>

            <Box
  sx={{
    display: 'flex',
    alignItems: 'center',
    gap: 2,
  }}
>

  <OrganizationSwitcher />

  <GlobalSearch
    value={search}
    onChange={setSearch}
  />

</Box>

          </Box>

          <IconButton
            onClick={() =>
              setNotificationsOpen(true)
            }
          >
            <Badge
              badgeContent={3}
              color="error"
            >
              <NotificationsNoneIcon />
            </Badge>
          </IconButton>

          <IconButton sx={{ ml: 1 }}>
            <DarkModeOutlinedIcon />
          </IconButton>

          <ProfileMenu />

        </Toolbar>
      </AppBar>

      <Drawer
        variant="permanent"
        sx={{
          width: drawerWidth,
          '& .MuiDrawer-paper': {
            width: drawerWidth,
            boxSizing: 'border-box',
            borderRight: '1px solid #e5e7eb',
          },
        }}
      >
        <Toolbar />

        {menu.map((section) => {

          const visibleItems = section.items.filter(
            (item) => hasPermission(item.permission)
          )

          if (visibleItems.length === 0) {
            return null
          }

          return (
            <Box key={section.group}>

              <Typography
                sx={{
                  px: 3,
                  pt: 3,
                  pb: 1,
                  fontSize: 12,
                  fontWeight: 700,
                  color: 'text.secondary',
                  textTransform: 'uppercase',
                }}
              >
                {section.group}
              </Typography>

              <List dense>

                {visibleItems.map((item) => (

                  <ListItemButton
                    key={item.title}
                    selected={
                      location.pathname === item.url
                    }
                    onClick={() =>
                      navigate(item.url)
                    }
                  >

                    <ListItemIcon>
                      {item.icon}
                    </ListItemIcon>

                    <ListItemText
                      primary={item.title}
                    />

                  </ListItemButton>

                ))}

              </List>

              <Divider sx={{ mt: 1 }} />

            </Box>
          )

        })}

      </Drawer>

      <Box
        component="main"
        sx={{
          flexGrow: 1,
          background: '#f4f7fb',
          minHeight: '100vh',
          p: 4,
        }}
      >
        <Toolbar />

        <BreadcrumbsBar />

        <Outlet />

      </Box>

      <NotificationDrawer
        open={notificationsOpen}
        onClose={() =>
          setNotificationsOpen(false)
        }
      />

    </Box>
  )
}