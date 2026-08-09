import {
  Divider,
  IconButton,
  MenuItem,
  Select,
  Stack,
  TextField,
  Tooltip,
} from '@mui/material'

import FormatBoldIcon from '@mui/icons-material/FormatBold'
import FormatItalicIcon from '@mui/icons-material/FormatItalic'
import FormatUnderlinedIcon from '@mui/icons-material/FormatUnderlined'
import FormatAlignLeftIcon from '@mui/icons-material/FormatAlignLeft'
import FormatAlignCenterIcon from '@mui/icons-material/FormatAlignCenter'
import FormatAlignRightIcon from '@mui/icons-material/FormatAlignRight'

import {
  useEffect,
  useState,
} from 'react'

import {
  IText,
  type Canvas,
} from 'fabric'

interface Props {
  canvas: Canvas | null
}

const FONTS = [
  'Arial',
  'Helvetica',
  'Inter',
  'Roboto',
  'Open Sans',
  'Lato',
  'Montserrat',
  'Georgia',
  'Times New Roman',
  'Verdana',
  'Trebuchet MS',
  'Courier New',
]

export default function FabricTextToolbar({
  canvas,
}: Props) {
  const [
    selectedText,
    setSelectedText,
  ] = useState<IText | null>(null)

  const [
    fontFamily,
    setFontFamily,
  ] = useState('Arial')

  const [
    fontSize,
    setFontSize,
  ] = useState(36)

  const [
    color,
    setColor,
  ] = useState('#111827')

  const [
    bold,
    setBold,
  ] = useState(false)

  const [
    italic,
    setItalic,
  ] = useState(false)

  const [
    underline,
    setUnderline,
  ] = useState(false)

  const [
    alignment,
    setAlignment,
  ] = useState<
    'left' | 'center' | 'right'
  >('left')

  useEffect(() => {
    if (!canvas) {
      setSelectedText(null)
      return
    }

    function updateSelection() {
      const active =
        canvas.getActiveObject()

      if (active instanceof IText) {
        setSelectedText(active)

        setFontFamily(
          active.fontFamily ?? 'Arial',
        )

        setFontSize(
          active.fontSize ?? 36,
        )

        setColor(
          typeof active.fill === 'string'
            ? active.fill
            : '#111827',
        )

        setBold(
          active.fontWeight === 'bold' ||
            Number(active.fontWeight) >= 600,
        )

        setItalic(
          active.fontStyle === 'italic',
        )

        setUnderline(
          Boolean(active.underline),
        )

        const align = active.textAlign

        if (align === 'center' || align === 'right') {
          setAlignment(align)
        } else {
          setAlignment('left')
        }

        return
      }

      setSelectedText(null)
    }

    canvas.on('selection:created', updateSelection)
    canvas.on('selection:updated', updateSelection)
    canvas.on('selection:cleared', updateSelection)
    canvas.on('object:modified', updateSelection)

    updateSelection()

    return () => {
      canvas.off('selection:created', updateSelection)
      canvas.off('selection:updated', updateSelection)
      canvas.off('selection:cleared', updateSelection)
      canvas.off('object:modified', updateSelection)
    }
  }, [canvas])

  function updateText(
    values: Record<string, unknown>,
  ) {
    if (!canvas || !selectedText) {
      return
    }

    selectedText.set(values)
    selectedText.setCoords()
    canvas.requestRenderAll()

    canvas.fire('object:modified', {
      target: selectedText,
    })
  }

  if (!selectedText) {
    return null
  }

  return (
    <Stack
      direction="row"
      spacing={0.75}
      alignItems="center"
      sx={{
        flexWrap: 'wrap',
        rowGap: 0.75,
      }}
    >
      <Divider orientation="vertical" flexItem />

      <Select
        size="small"
        value={fontFamily}
        sx={{ width: 170 }}
        onChange={(event) => {
          const value = String(event.target.value)

          setFontFamily(value)
          updateText({ fontFamily: value })
        }}
      >
        {FONTS.map((font) => (
          <MenuItem
            key={font}
            value={font}
            sx={{ fontFamily: font }}
          >
            {font}
          </MenuItem>
        ))}
      </Select>

      <TextField
        size="small"
        type="number"
        value={fontSize}
        inputProps={{ min: 6, max: 300, step: 1 }}
        sx={{ width: 80 }}
        onChange={(event) => {
          const value = Number(event.target.value)

          if (!Number.isFinite(value) || value < 6 || value > 300) {
            return
          }

          setFontSize(value)
          updateText({ fontSize: value })
        }}
      />

      <Tooltip title="Text color">
        <TextField
          size="small"
          type="color"
          value={color}
          sx={{
            width: 48,
            '& input': {
              p: 0.5,
              cursor: 'pointer',
            },
          }}
          onChange={(event) => {
            const value = event.target.value
            setColor(value)
            updateText({ fill: value })
          }}
        />
      </Tooltip>

      <Tooltip title="Bold">
        <IconButton
          size="small"
          color={bold ? 'primary' : 'default'}
          onClick={() => {
            const next = !bold
            setBold(next)
            updateText({
              fontWeight: next ? 'bold' : 'normal',
            })
          }}
        >
          <FormatBoldIcon />
        </IconButton>
      </Tooltip>

      <Tooltip title="Italic">
        <IconButton
          size="small"
          color={italic ? 'primary' : 'default'}
          onClick={() => {
            const next = !italic
            setItalic(next)
            updateText({
              fontStyle: next ? 'italic' : 'normal',
            })
          }}
        >
          <FormatItalicIcon />
        </IconButton>
      </Tooltip>

      <Tooltip title="Underline">
        <IconButton
          size="small"
          color={underline ? 'primary' : 'default'}
          onClick={() => {
            const next = !underline
            setUnderline(next)
            updateText({ underline: next })
          }}
        >
          <FormatUnderlinedIcon />
        </IconButton>
      </Tooltip>

      <Divider orientation="vertical" flexItem />

      <Tooltip title="Align left">
        <IconButton
          size="small"
          color={alignment === 'left' ? 'primary' : 'default'}
          onClick={() => {
            setAlignment('left')
            updateText({ textAlign: 'left' })
          }}
        >
          <FormatAlignLeftIcon />
        </IconButton>
      </Tooltip>

      <Tooltip title="Align center">
        <IconButton
          size="small"
          color={alignment === 'center' ? 'primary' : 'default'}
          onClick={() => {
            setAlignment('center')
            updateText({ textAlign: 'center' })
          }}
        >
          <FormatAlignCenterIcon />
        </IconButton>
      </Tooltip>

      <Tooltip title="Align right">
        <IconButton
          size="small"
          color={alignment === 'right' ? 'primary' : 'default'}
          onClick={() => {
            setAlignment('right')
            updateText({ textAlign: 'right' })
          }}
        >
          <FormatAlignRightIcon />
        </IconButton>
      </Tooltip>
    </Stack>
  )
}