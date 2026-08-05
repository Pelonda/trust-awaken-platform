import {
  Card,
  CardContent,
  Divider,
  List,
  ListItem,
  ListItemText,
  Typography,
} from '@mui/material'

const sessions = [
  {
    title: 'Cybersecurity Bootcamp',
    date: 'Aug 12',
  },
  {
    title: 'Networking Essentials',
    date: 'Aug 15',
  },
  {
    title: 'Cloud Fundamentals',
    date: 'Aug 18',
  },
]

export default function UpcomingSessions() {
  return (
    <Card
      elevation={0}
      sx={{
        borderRadius: 4,
        border: '1px solid #e5e7eb',
        height: '100%',
      }}
    >
      <CardContent>

        <Typography
          variant="h6"
          fontWeight={700}
          sx={{ mb: 2 }}
        >
          Upcoming Sessions
        </Typography>

        <List>

          {sessions.map((session, index) => (

            <div key={session.title}>

              <ListItem>

                <ListItemText
                  primary={session.title}
                  secondary={session.date}
                />

              </ListItem>

              {index < sessions.length - 1 && (
                <Divider />
              )}

            </div>

          ))}

        </List>

      </CardContent>
    </Card>
  )
}