import {
  Avatar,
  Card,
  CardContent,
  Divider,
  List,
  ListItem,
  ListItemAvatar,
  ListItemText,
  Typography,
} from '@mui/material'

const participants = [
  {
    name: 'John Doe',
    code: 'P0001',
  },
  {
    name: 'Jane Smith',
    code: 'P0002',
  },
  {
    name: 'Michael Brown',
    code: 'P0003',
  },
  {
    name: 'Sarah Wilson',
    code: 'P0004',
  },
]

export default function LatestParticipants() {
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
          Latest Participants
        </Typography>

        <List>

          {participants.map((participant, index) => (

            <div key={participant.code}>

              <ListItem>

                <ListItemAvatar>

                  <Avatar>
                    {participant.name.charAt(0)}
                  </Avatar>

                </ListItemAvatar>

                <ListItemText
                  primary={participant.name}
                  secondary={participant.code}
                />

              </ListItem>

              {index < participants.length - 1 && (
                <Divider />
              )}

            </div>

          ))}

        </List>

      </CardContent>
    </Card>
  )
}