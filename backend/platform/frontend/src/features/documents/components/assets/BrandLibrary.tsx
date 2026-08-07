import {
  MenuItem,
  Select,
  Stack,
  Typography,
} from '@mui/material'

import useBrand from '../../hooks/useBrand'

interface Props {
  onChange: (theme: string) => void
}

export default function BrandLibrary({
  onChange,
}: Props) {

  const {

    themes,

    themeId,

    setThemeId,

  } = useBrand()

  return (

    <Stack spacing={2}>

      <Typography
        variant="h6"
        fontWeight={700}
      >
        Brand
      </Typography>

      <Select
        size="small"
        value={themeId}
        onChange={(e) => {

          const id =
            e.target.value

          setThemeId(id)

          onChange(id)

        }}
      >

        {themes.map(theme => (

          <MenuItem
            key={theme.id}
            value={theme.id}
          >
            {theme.name}
          </MenuItem>

        ))}

      </Select>

    </Stack>

  )

}