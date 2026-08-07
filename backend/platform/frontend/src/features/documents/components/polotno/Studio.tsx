import {
  PolotnoContainer,
  SidePanelWrap,
  WorkspaceWrap,
} from 'polotno'

import { SidePanel } from 'polotno/side-panel'
import { Toolbar } from 'polotno/toolbar/toolbar'
import { ZoomButtons } from 'polotno/toolbar/zoom-buttons'
import { PagesTimeline } from 'polotno/pages-timeline'
import { Workspace } from 'polotno/canvas/workspace'
import { createStore } from 'polotno/model/store'

import 'polotno/ui.css'

const store = createStore({
  showCredit: true,
})

if (store.pages.length === 0) {
  store.addPage()
}

export default function Studio() {
  return (
    <PolotnoContainer
      style={{
        width: '100%',
        height: '100%',
      }}
    >
      <SidePanelWrap>
        <SidePanel store={store} />
      </SidePanelWrap>

      <WorkspaceWrap>
        <Toolbar
          store={store}
          downloadButtonEnabled={false}
        />

        <Workspace store={store} />

        <ZoomButtons store={store} />

        <PagesTimeline store={store} />
      </WorkspaceWrap>
    </PolotnoContainer>
  )
}