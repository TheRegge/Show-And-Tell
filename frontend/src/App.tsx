import React from 'react'
// import { useSelector } from 'react-redux'

// Local imports
import FormsList from './components/FormsList'
import FormEditor from './components/FormEditor'
import NewFormButton from './components/NewFormButton'
import BrandingHeader from './components/Dashboard/BrandingHeader'
// import FormSettings from './components/FormEditor/FormSettings'

import './App.css'

const App: React.FC = () => (
  <>
    <div id="sat-app" className="container mx-auto px-8 my-8 mx-w-3xl">
      <BrandingHeader />
      <div className="flex flex-row justify-between items-baseline mb-6 mt-8">
        <h1 className="text-3xl font-extralight">
          Forms
        </h1>
        <NewFormButton />
      </div>
      <FormsList />
    </div>
    <FormEditor />
  </>
)

export default App
