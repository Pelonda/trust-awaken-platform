import {
  Card,
  CardActionArea,
  CardContent,
  Typography,
} from '@mui/material'

import { motion } from 'framer-motion'
import { ReactNode } from 'react'

interface Props {
  title: string
  subtitle: string
  icon: ReactNode
  onClick: () => void
}

export default function QuickActionCard({
  title,
  subtitle,
  icon,
  onClick,
}: Props) {
  return (
    <motion.div whileHover={{ scale: 1.03 }}>
      <Card
        elevation={0}
        sx={{
          border: '1px solid #e5e7eb',
          borderRadius: 3,
          height: '100%',
        }}
      >
        <CardActionArea onClick={onClick}>
          <CardContent>

            <Typography
              sx={{ mb: 2 }}
              color="primary"
            >
              {icon}
            </Typography>

            <Typography
              variant="h6"
              fontWeight={700}
            >
              {title}
            </Typography>

            <Typography
              color="text.secondary"
            >
              {subtitle}
            </Typography>

          </CardContent>
        </CardActionArea>
      </Card>
    </motion.div>
  )
}