import {
  Alert,
  Box,
  Button,
  CircularProgress,
  Divider,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import SaveIcon from '@mui/icons-material/Save'

import {
  useEffect,
  useState,
} from 'react'

import useBrand from '../../hooks/useBrand'

interface FormState {
  brand_name: string

  logo_path: string
  signature_path: string
  seal_path: string
  watermark_path: string
  background_path: string

  primary_color: string
  secondary_color: string
  accent_color: string

  font_family: string
}

const EMPTY_FORM: FormState = {
  brand_name: '',

  logo_path: '',
  signature_path: '',
  seal_path: '',
  watermark_path: '',
  background_path: '',

  primary_color: '#1d4ed8',
  secondary_color: '#0f172a',
  accent_color: '#f59e0b',

  font_family: 'Arial',
}

export default function BrandSettings() {
  const {
    brand,
    loading,
    saving,
    error,
    save,
  } = useBrand()

  const [
    form,
    setForm,
  ] = useState<FormState>(
    EMPTY_FORM,
  )

  const [
    success,
    setSuccess,
  ] = useState<
    string | null
  >(null)

  useEffect(() => {
    if (!brand) {
      return
    }

    setForm({
      brand_name:
        brand.brand_name ?? '',

      logo_path:
        brand.logo_path ?? '',

      signature_path:
        brand.signature_path ?? '',

      seal_path:
        brand.seal_path ?? '',

      watermark_path:
        brand.watermark_path ?? '',

      background_path:
        brand.background_path ?? '',

      primary_color:
        brand.primary_color ??
        '#1d4ed8',

      secondary_color:
        brand.secondary_color ??
        '#0f172a',

      accent_color:
        brand.accent_color ??
        '#f59e0b',

      font_family:
        brand.font_family ??
        'Arial',
    })
  }, [brand])

  function change(
    field: keyof FormState,
    value: string,
  ) {
    setForm(
      current => ({
        ...current,
        [field]: value,
      }),
    )
  }

  async function handleSave() {
    setSuccess(null)

    await save({
      brand_name:
        form.brand_name ||
        null,

      logo_path:
        form.logo_path ||
        null,

      signature_path:
        form.signature_path ||
        null,

      seal_path:
        form.seal_path ||
        null,

      watermark_path:
        form.watermark_path ||
        null,

      background_path:
        form.background_path ||
        null,

      primary_color:
        form.primary_color ||
        null,

      secondary_color:
        form.secondary_color ||
        null,

      accent_color:
        form.accent_color ||
        null,

      font_family:
        form.font_family ||
        null,
    })

    setSuccess(
      'Brand settings saved.',
    )
  }

  if (loading) {
    return (
      <Box
        sx={{
          display: 'flex',
          justifyContent:
            'center',
          py: 5,
        }}
      >
        <CircularProgress />
      </Box>
    )
  }

  return (
    <Box>
      <Typography
        variant="h6"
        fontWeight={700}
      >
        Brand Settings
      </Typography>

      <Typography
        variant="body2"
        color="text.secondary"
        sx={{
          mt: 0.5,
        }}
      >
        Configure document branding
        for the selected organization.
      </Typography>

      <Divider sx={{ my: 2 }} />

      {error && (
        <Alert
          severity="error"
          sx={{
            mb: 2,
          }}
        >
          {error}
        </Alert>
      )}

      {success && (
        <Alert
          severity="success"
          sx={{
            mb: 2,
          }}
        >
          {success}
        </Alert>
      )}

      <Stack spacing={2}>
        <TextField
          label="Brand Name"
          size="small"
          value={
            form.brand_name
          }
          onChange={event =>
            change(
              'brand_name',
              event.target.value,
            )
          }
        />

        <Typography
          variant="subtitle2"
          fontWeight={700}
        >
          Assets
        </Typography>

        <TextField
          label="Logo Path"
          size="small"
          value={
            form.logo_path
          }
          onChange={event =>
            change(
              'logo_path',
              event.target.value,
            )
          }
        />

        <TextField
          label="Signature Path"
          size="small"
          value={
            form.signature_path
          }
          onChange={event =>
            change(
              'signature_path',
              event.target.value,
            )
          }
        />

        <TextField
          label="Seal Path"
          size="small"
          value={
            form.seal_path
          }
          onChange={event =>
            change(
              'seal_path',
              event.target.value,
            )
          }
        />

        <TextField
          label="Watermark Path"
          size="small"
          value={
            form.watermark_path
          }
          onChange={event =>
            change(
              'watermark_path',
              event.target.value,
            )
          }
        />

        <TextField
          label="Background Path"
          size="small"
          value={
            form.background_path
          }
          onChange={event =>
            change(
              'background_path',
              event.target.value,
            )
          }
        />

        <Divider />

        <Typography
          variant="subtitle2"
          fontWeight={700}
        >
          Colors
        </Typography>

        <TextField
          label="Primary Color"
          size="small"
          type="color"
          value={
            form.primary_color
          }
          onChange={event =>
            change(
              'primary_color',
              event.target.value,
            )
          }
        />

        <TextField
          label="Secondary Color"
          size="small"
          type="color"
          value={
            form.secondary_color
          }
          onChange={event =>
            change(
              'secondary_color',
              event.target.value,
            )
          }
        />

        <TextField
          label="Accent Color"
          size="small"
          type="color"
          value={
            form.accent_color
          }
          onChange={event =>
            change(
              'accent_color',
              event.target.value,
            )
          }
        />

        <TextField
          label="Font Family"
          size="small"
          value={
            form.font_family
          }
          onChange={event =>
            change(
              'font_family',
              event.target.value,
            )
          }
        />

        <Button
          variant="contained"
          startIcon={
            saving
              ? (
                  <CircularProgress
                    size={16}
                    color="inherit"
                  />
                )
              : (
                  <SaveIcon />
                )
          }
          disabled={saving}
          onClick={() =>
            void handleSave()
          }
        >
          Save Brand
        </Button>
      </Stack>
    </Box>
  )
}